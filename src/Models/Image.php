<?php

namespace Eval4VictoryCTO\LaravelImageUploader\Models;

use Eval4VictoryCTO\LaravelImageUploader\Facades\ImageUploader;
use \Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($image)
        {
            foreach ($image->copies as $imageCopy) {
                ImageUploader::deleteCopy($imageCopy->file_name);
            }

            ImageUploader::deleteImage($image->file_name);
        });
    }

    public function copies()
    {
        return $this->hasMany(ImageCopy::class);
    }

    public function scopeResized($query)
    {
        return $query->where('resized', 1);
    }
}
