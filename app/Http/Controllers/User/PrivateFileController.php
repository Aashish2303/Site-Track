<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\PrivateUploads;
use App\Services\PublicReference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function show(string $kind, string $reference, PrivateUploads $uploads, PublicReference $refs)
    {
        $resources = ['profile' => ['users', 'user_id', 'user_image'], 'vendor' => ['vendors', 'vendor_id', 'vendor_card'],
            'issue' => ['issues', 'issue_id', 'issue_image'], 'quotation' => ['indent_quotations', 'iquote_id', 'iquote_quotation']];
        abort_unless(isset($resources[$kind]), 404);
        [$table, $primary, $field] = $resources[$kind];
        $id = $refs->decode($reference, $table);
        if ($kind === 'profile') {
            abort_unless($id === (int) Auth::id() || \Helper::userHasPermission('user.edit_user'), 403);
        } elseif ($kind === 'vendor') {
            abort_unless(\Helper::userHasPermission('user.vendors') || \Helper::userHasPermission('user.edit_vendor'), 403);
        } else {
            $permission = $kind === 'issue' ? 'user.issues' : 'user.indent_quotations';
            abort_unless(\Helper::userHasPermission($permission), 403);
            $site = session('workspace_project_id');
            abort_unless($site, 403);
            $matches = $kind === 'issue'
                ? DB::table('issues')->where('issue_id', $id)->where('issue_project', $site)->exists()
                : DB::table('indent_quotations')->join('indents', 'indent_id', '=', 'iquote_indent')->where('iquote_id', $id)->where('indent_project', $site)->exists();
            abort_unless($matches, 404);
        }
        $name = DB::table($table)->where($primary, $id)->value($field);
        abort_unless($name && $uploads->legacyPath($kind, $name), 404);
        $uploads->migrate($kind, $name);
        abort_unless(Storage::disk('personal')->exists($kind.'/'.$name), 404);
        $mime = Storage::disk('personal')->mimeType($kind.'/'.$name);
        $inline = in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true);

        return Storage::disk('personal')->response($kind.'/'.$name, 'document.'.($inline ? 'jpg' : 'pdf'),
            ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff', 'Cross-Origin-Resource-Policy' => 'same-origin'], $inline ? 'inline' : 'attachment');
    }
}
