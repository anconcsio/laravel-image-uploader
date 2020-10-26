<?php

use Illuminate\Support\Facades\Route;
use Eval4VictoryCTO\LaravelImageUploader\Http\Controllers\ImageController;

Route::get('/image-uploader/images', [ImageController::class, 'images'])->name('image-uploader.api-images');
