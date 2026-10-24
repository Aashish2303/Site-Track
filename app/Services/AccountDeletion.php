<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountDeletion
{
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            // Lock administrator rows in a common order so concurrent erasures cannot remove the last two admins.
            $administrators = User::where('user_role', 1)->where('user_status', 1)->orderBy('user_id')->lockForUpdate()->get(['user_id']);
            $user = User::whereKey($user->user_id)->lockForUpdate()->firstOrFail();
            if ((int) $user->user_role === 1 && $administrators->count() <= 1) {
                throw ValidationException::withMessages(['confirmation' => 'Create another active administrator before deleting this account.']);
            }
            $id = (int) $user->user_id;
            $identities = array_values(array_filter([$user->user_email, $user->user_phone, $user->user_name, $user->user_lname], fn ($s) => is_string($s) && mb_strlen($s) >= 3));
            $anonymous = User::where('user_lname', '__privacy_deleted__')->first();
            if (! $anonymous) {
                $anonymous = $user->replicate();
                $anonymous->forceFill(['user_name' => 'Deleted account', 'user_lname' => '__privacy_deleted__', 'user_email' => '',
                    'user_phone' => '', 'user_password' => Hash::make(Str::random(64)), 'user_vpassword' => '', 'user_image' => null,
                    'user_status' => 0, 'user_added_by' => 0, 'user_updated_by' => 0])->save();
            }
            $ownedProgress = DB::table('daily_progress')->where('dprogress_added_by', $id)->orWhere('dprogress_updated_by', $id)->pluck('dprogress_id')->all();
            $ownedPhotos = \Schema::hasTable('progress_photos') ? DB::table('progress_photos')->where('uploaded_by', $id)->get() : collect();
            $columns = DB::select('SELECT TABLE_NAME AS table_name, COLUMN_NAME AS column_name, DATA_TYPE AS data_type FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?', [DB::getDatabaseName()]);
            $tables = [];
            foreach ($columns as $column) {
                $tables[$column->table_name][$column->column_name] = $column->data_type;
            }
            foreach ($tables as $table => $fields) {
                if (! preg_match('/\A[a-z_]+\z/', $table) || in_array($table, ['sessions', 'password_reset_tokens', 'personal_access_tokens'], true)) {
                    continue;
                }
                $references = array_values(array_filter(array_keys($fields), fn ($name) => preg_match('/_(added_by|updated_by|engineer|incharge)$/', $name) || in_array($name, ['issue_assign', 'actor_id', 'reviewer_id', 'uploaded_by', 'created_by'], true)));
                if (! $references) {
                    continue;
                }
                $primary = DB::select('SHOW KEYS FROM `'.$table.'` WHERE Key_name = ?', ['PRIMARY']);
                $primary = $primary[0]->Column_name ?? null;
                if ($primary && $table !== 'users') {
                    $linked = DB::table($table)->where(function ($q) use ($references, $id, $table, $ownedProgress) {
                        foreach ($references as $field) {
                            $q->orWhere($field, $id);
                        } if ($table === 'progress_events' && $ownedProgress) {
                            $q->orWhereIn('progress_id', $ownedProgress);
                        }
                    })->get();
                    foreach ($linked as $row) {
                        $changes = [];
                        foreach ($fields as $field => $type) {
                            if (! in_array($type, ['varchar', 'text', 'mediumtext', 'longtext', 'json'], true) || ! is_string($row->$field) || preg_match('/(password|path|image|photo|quotation|card|submission_key)/', $field)) {
                                continue;
                            }
                            $value = $row->$field;
                            foreach ($identities as $identity) {
                                $value = preg_replace('/(?<![\pL\pN])'.preg_quote($identity, '/').'(?![\pL\pN])/iu', '[Deleted account]', $value);
                            }
                            if (in_array($field, ['before_data', 'after_data'], true)) {
                                $decoded = json_decode($value, true);
                                $value = json_encode($this->anonymiseSnapshot($decoded, $id, $anonymous->user_id), JSON_UNESCAPED_UNICODE);
                            }
                            if ($value !== $row->$field) {
                                $changes[$field] = $value;
                            }
                        }
                        if ($changes) {
                            DB::table($table)->where($primary, $row->$primary)->update($changes);
                        }
                    }
                }
                foreach ($references as $field) {
                    DB::table($table)->where($field, $id)->update([$field => $anonymous->user_id]);
                }
            }
            foreach ($ownedPhotos as $photo) {
                Storage::disk('evidence')->delete($photo->path);
                DB::table('progress_photos')->where('id', $photo->id)->delete();
            }
            app(PrivateUploads::class)->delete('profile', $user->user_image);
            DB::table('password_reset_tokens')->where('email', $user->user_email)->delete();
            if (\Schema::hasTable('personal_access_tokens')) {
                DB::table('personal_access_tokens')->where('tokenable_type', User::class)->where('tokenable_id', $id)->delete();
            }
            if (\Schema::hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $id)->delete();
            }
            $this->deleteSessions($id);
            DB::table('users')->where('user_id', $id)->delete();
        });
    }

    private function anonymiseSnapshot(mixed $value, int $old, int $anonymous): mixed
    {
        if (! is_array($value)) {
            return $value;
        }
        foreach ($value as $key => &$item) {
            if (preg_match('/_(added_by|updated_by|engineer|incharge)$/', (string) $key) || in_array($key, ['actor_id', 'reviewer_id', 'uploaded_by', 'created_by'], true)) {
                if ((string) $item === (string) $old) {
                    $item = $anonymous;
                }
            } elseif (is_array($item)) {
                $item = $this->anonymiseSnapshot($item, $old, $anonymous);
            }
        }

        return $value;
    }

    private function deleteSessions(int $id): void
    {
        foreach (File::files(storage_path('framework/sessions')) as $file) {
            if (! preg_match('/\A[A-Za-z0-9]{40}\z/', $file->getFilename()) || $file->isLink()) {
                continue;
            }
            try {
                $content = Crypt::decrypt(file_get_contents($file->getPathname()));
                $data = @unserialize($content, ['allowed_classes' => false]);
                if (! is_array($data)) {
                    continue;
                }
                foreach ($data as $key => $value) {
                    if (str_starts_with($key, 'login_web_') && (int) $value === $id) {
                        unlink($file->getPathname());
                        break;
                    }
                }
            } catch (DecryptException $e) { /* Legacy sessions are invalid and are purged by privacy:upgrade. */
            }
        }
    }
}
