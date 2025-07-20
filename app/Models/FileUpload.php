<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class FileUpload extends Model {

    public static function uploadImage($file): string {
        if (!str_starts_with($file->getMimeType(), 'image/')) {
            throw new \Exception('O arquivo deve ser uma imagem');
        }

        $imageManager = new ImageManager(new Driver());
        $image = $imageManager->read($file->get());
        $image->resize(200, 200);
        
        // Define o caminho relativo
        $filename = Str::slug($file->getClientOriginalName(), '-') . '_' . Carbon::now()->format('Ymd-His') . '.webp';
        $relativePath = 'uploads/' . Carbon::now()->year . '/' . $filename;

        // Armazena o arquivo usando o Storage do Laravel
        Storage::disk('public')->put($relativePath, $image->toWebp(80));

        return $relativePath;
    }

    public static function deleteImage($path): void {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
