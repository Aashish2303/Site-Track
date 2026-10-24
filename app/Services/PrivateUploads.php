<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PrivateUploads
{
    public const DIRECTORIES = ['profile', 'vendor', 'issue', 'quotation'];

    public function store(UploadedFile $file, string $kind): string
    {
        if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) {
            $name = Str::random(40).'.jpg';
            Storage::disk('personal')->put($kind.'/'.$name, $this->imageBytes($file->getRealPath()));

            return $name;
        }

        return basename($file->store($kind, 'personal'));
    }

    public function imageBytes(string $path): string
    {
        $size = @getimagesize($path);
        if (! $size || $size[0] * $size[1] > 12000000 || max($size[0], $size[1]) > 6000) {
            throw ValidationException::withMessages(['photos' => 'Please upload an image no larger than 12 megapixels.']);
        }
        $image = @imagecreatefromstring(file_get_contents($path));
        if (! $image) {
            throw ValidationException::withMessages(['photos' => 'Unable to read this image.']);
        }
        if (function_exists('exif_read_data') && ($size['mime'] ?? '') === 'image/jpeg') {
            $metadata = @exif_read_data($path, 'IFD0', true, false);
            $orientation = (int) ($metadata['IFD0']['Orientation'] ?? 1);
            if (in_array($orientation, [2, 5, 7], true)) {
                imageflip($image, IMG_FLIP_HORIZONTAL);
            }
            if ($orientation === 4) {
                imageflip($image, IMG_FLIP_VERTICAL);
            }
            $angle = match ($orientation) {
                3 => 180, 5, 6 => -90, 7, 8 => 90, default => 0
            };
            if ($angle) {
                $rotated = imagerotate($image, $angle, 0);
                imagedestroy($image);
                $image = $rotated;
            }
        }
        ob_start();
        imagejpeg($image, null, 90);
        $bytes = ob_get_clean();
        imagedestroy($image);

        // Re-encoding removes EXIF/GPS, comments, original filenames and camera metadata.
        return $bytes;
    }

    public function legacyPath(string $kind, string $name): ?string
    {
        if (! in_array($kind, self::DIRECTORIES, true) || basename($name) !== $name || ! preg_match('/\A[A-Za-z0-9_.-]+\z/', $name)) {
            return null;
        }

        return public_path('assets/admin/uploads/'.$kind.'/'.$name);
    }

    public function migrate(string $kind, string $name): void
    {
        $legacy = $this->legacyPath($kind, $name);
        if ($legacy && is_file($legacy) && ! is_link($legacy)) {
            if (! Storage::disk('personal')->exists($kind.'/'.$name)) {
                Storage::disk('personal')->put($kind.'/'.$name, file_get_contents($legacy));
            }
            unlink($legacy);
        }
    }

    public function delete(string $kind, ?string $name): void
    {
        if (! $name || ! $this->legacyPath($kind, $name)) {
            return;
        }
        Storage::disk('personal')->delete($kind.'/'.$name);
        $legacy = $this->legacyPath($kind, $name);
        if (is_file($legacy) && ! is_link($legacy)) {
            unlink($legacy);
        }
    }
}
