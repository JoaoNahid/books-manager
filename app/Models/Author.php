<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    protected $fillable = [
        'name',
        'active'
    ];

    public function books(): HasMany {
        return $this->hasMany(Book::class);
    }

    public static function getAuthors($id = null): array {
        return $id ? self::find($id)->toArray() : self::all()->toArray();
    }

    public static function post($data): self|false {
        try {
            $author = self::updateOrCreate( ['id' => $data['id'] ?? null], $data );
            return $author;
        } catch (\Exception $exception) {
            Log::error('Failed to create or update author: ' . $exception->getMessage());
            return false;
        }

    }
}
