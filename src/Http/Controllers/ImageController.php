<?php

namespace Eval4VictoryCTO\LaravelImageUploader\Http\Controllers;

use Eval4VictoryCTO\LaravelImageUploader\Facades\ImageUploader;
use Eval4VictoryCTO\LaravelImageUploader\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ImageController extends Controller
{
    use ValidatesRequests;

    public function form()
    {
        return view('image-uploader::form');
    }

    public function images()
    {
        $images = Image::with('copies')->resized()->latest()->take(5)->get();
        return $images;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|image'
        ]);

        $imageFile = $request->file('file');

        try {
            ImageUploader::saveImage($imageFile);
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors('Error of the image uploading: ' . $e->getMessage());
        }

        return redirect(route('image-uploader.form'))->with('status', 'Image was uploaded and queued for processing');
    }
}
