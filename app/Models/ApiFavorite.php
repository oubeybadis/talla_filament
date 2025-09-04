<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiFavorite extends Model
{
    protected $fillable = [
        'artwork_id',
        'artwork_title',
        'api_image_url',
    ];
}
