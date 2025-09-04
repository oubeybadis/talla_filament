<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedImage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'path',
        'size',
        'isFavorite'
    ];
    protected $casts = [
        'isFavorite' => 'boolean',
        'size' => 'integer'
    ];
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($image) {
            if ($image->file_path && Storage::exists($image->file_path)) {
                Storage::delete($image->file_path);
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        return $this->file_path ? Storage::url($this->file_path) : '';
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
