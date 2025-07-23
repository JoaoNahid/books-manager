<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'description',
        'published_at',
        'author_id',
        'image'
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function author() {
        return $this->belongsTo(Author::class);
    }

    public static function getBooks() {
        return self::select()->with('author');
    }

    public static function post($data): self|false {
        try {
            $book = self::query()->updateOrCreate(['id' => $data['id'] ?? null], $data);
            if (isset($data['file'])) {
                $uploadedImage = FileUpload::uploadImage($data['file']);
                if ($book->image) {
                    FileUpload::deleteImage($book->image);
                }
                $book->image = $uploadedImage;
                $book->save();
            }
            return $book;
        } catch (\Exception $exception) {
            Log::create('Fail to create or update a book register: ' . $exception->getMessage());
            return false;
        }
    }
}
