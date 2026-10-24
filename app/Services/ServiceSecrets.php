<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/** SMTP transport credentials are separate from user login passwords. */
class ServiceSecrets
{
    public function storeSmtp(#[\SensitiveParameter] string $secret): void
    {
        Storage::disk('personal')->put('integration-secrets/smtp.enc', Crypt::encryptString($secret));
    }

    public function smtp(Settings $settings): ?string
    {
        if (filled($settings->getRawOriginal('settings_pwd'))) {
            $this->storeSmtp($settings->getRawOriginal('settings_pwd'));
            DB::table('settings')->where('settings_id', $settings->settings_id)->update(['settings_pwd' => '']);
            $settings->settings_pwd = '';
        }

        return Storage::disk('personal')->exists('integration-secrets/smtp.enc')
            ? Crypt::decryptString(Storage::disk('personal')->get('integration-secrets/smtp.enc'))
            : config('mail.mailers.smtp.password');
    }
}
