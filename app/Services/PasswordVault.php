<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/** Authenticated encryption for the explicitly requested admin password display. */
class PasswordVault
{
    public const PREFIX = 'enc:v1:';

    private function encrypter(): Encrypter
    {
        $key = config('app.key');
        $key = str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7), true) : $key;
        if (! is_string($key) || strlen($key) < 32) {
            throw new \RuntimeException('Password encryption is not configured.');
        }

        // A separate purpose-derived key; never stored alongside the database.
        return new Encrypter(hash_hkdf('sha256', $key, 32, 'sitetrack.admin-password.v1'), 'aes-256-gcm');
    }

    public function encrypt(#[\SensitiveParameter] string $password): string
    {
        $encrypted = self::PREFIX.$this->encrypter()->encryptString($password);
        if (strlen($encrypted) > 255) {
            throw new \InvalidArgumentException('Password exceeds the supported length.');
        }

        return $encrypted;
    }

    public function decrypt(#[\SensitiveParameter] string $encrypted): string
    {
        if (! str_starts_with($encrypted, self::PREFIX)) {
            return '';
        }
        try {
            return $this->encrypter()->decryptString(substr($encrypted, strlen(self::PREFIX)));
        } catch (DecryptException $e) {
            // Do not reveal invalid/tampered ciphertext or fall back to Base64.
            return '';
        }
    }

    /** Idempotent, bounded upgrade; compare-and-swap preserves concurrent password changes. */
    public function upgradeLegacyBatch(int $limit = 200): int
    {
        $users = DB::table('users')->select('user_id', 'user_password', 'user_vpassword')
            ->where('user_vpassword', '<>', '')->where('user_vpassword', 'not like', self::PREFIX.'%')
            ->orderBy('user_id')->limit($limit)->get();
        foreach ($users as $user) {
            $password = base64_decode($user->user_vpassword, true);
            $valid = is_string($password) && strlen($password) <= 72 && Hash::check($password, $user->user_password);
            DB::table('users')->where('user_id', $user->user_id)->where('user_vpassword', $user->user_vpassword)
                ->update(['user_vpassword' => $valid ? $this->encrypt($password) : '']);
        }

        return $users->count();
    }
}
