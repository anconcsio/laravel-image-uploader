<?php

namespace Eval4VictoryCTO\LaravelImageUploader\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Eval4VictoryCTO\LaravelImageUploader\Facades\ImageUploader;

class ProcessUploadedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;
    public $deleteWhenMissingModels = true;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function handle()
    {
        ImageUploader::resizeImage($this->image);
    }
}
