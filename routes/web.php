<?php

use Illuminate\Support\Facades\Route;
use Eval4VictoryCTO\LaravelImageUploader\Http\Controllers\ImageController;

Route::get('/image-uploader', [ImageController::class, 'form'])->name('image-uploader.form');
Route::post('/image-uploader', [ImageController::class, 'store'])->name('image-uploader.store');
