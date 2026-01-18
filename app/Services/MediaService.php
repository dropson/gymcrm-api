<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class MediaService
{
    public function upload(
        UploadedFile $file,
        string $directory,
        ?string $oldPath = null
    ): string {
        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $filename = time().'_'.Str::random(8).'_'.$file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
