<?php

namespace Eval4VictoryCTO\LaravelImageUploader;

use Eval4VictoryCTO\LaravelImageUploader\Events\ImageResized;
use Eval4VictoryCTO\LaravelImageUploader\Exceptions\FileSaveException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Eval4VictoryCTO\LaravelImageUploader\Models\Image;
use Eval4VictoryCTO\LaravelImageUploader\Models\ImageCopy;
use Eval4VictoryCTO\LaravelImageUploader\Jobs\ProcessUploadedImage;
use Intervention\Image\ImageManagerStatic as ImageManager;

class ImageUploader
{
    protected $savePath, $tmpSavePath, $resizeTo, $queueName, $queueConnectionName, $defaultImageType;

    public function __construct()
    {
        $this->savePath = config('image-uploader.save-path', '');
        if ($this->savePath)
            $this->savePath .= '/';

        $this->tmpSavePath = config('image-uploader.tmp-save-path');
        $this->resizeTo = config('image-uploader.sizes');
        $this->queueConnectionName = config('image-uploader.queue-connection', config('queue.default'));
        $this->queueName = config('image-uploader.queue');
        $this->defaultImageType = 'jpeg';

        $this->storage = Storage::disk(config('image-uploader.storage', config('filesystems.cloud')));
        $this->storageTmp = Storage::disk(config('image-uploader.tmp-storage'));
    }

    /**
     * Saves uploaded image file and creates new task of the image resizing.
     *
     * @param UploadedFile $image
     */
    public function saveImage(UploadedFile $image)
    {
        $fileName = $this->storageTmp->putFile($this->tmpSavePath, $image);
        if (! $fileName) {
            throw new FileSaveException();
        }

        $imageModel = new Image();
        $imageModel->original_name = $image->getClientOriginalName();
        $imageModel->file_name = $fileName;
        $imageModel->save();

        ProcessUploadedImage::
            dispatch($imageModel)
            ->onConnection($this->queueConnectionName)
            ->onQueue($this->queueName);
    }

    public function resizeImage(Image $image)
    {
        $resizedImages = [];
        $originalImageFilename = $this->storageTmp->path($image->file_name);

        foreach ($this->resizeTo as $sizeName => $size) {
            $newImage = ImageManager::make($originalImageFilename);

            if ($size['width'] ?? 0 && $size['height'] ?? 0) {
                $newImage->resize($size['width'], $size['height']);
            }

            $imageType = $size['type'] ?? $this->defaultImageType;
            $newImageStr = (string) $newImage->encode($imageType);
            $newImage->destroy();

            $newImageFilename = $this->savePath . $sizeName . '-' . $image->id . '.' . $imageType;
            $this->storage->put($newImageFilename, $newImageStr, ['visibility' => 'public']);

            $imageModel = new ImageCopy();
            $imageModel->image_id = $image->id;
            $imageModel->file_name = $newImageFilename;
            $imageModel->url = $this->storage->url($newImageFilename);
            $imageModel->image_type = $imageType;
            $imageModel->size_type = $sizeName;
            $imageModel->save();

            $resizedImages[$sizeName] = $imageModel->url;
        }

        $image->resized = 1;
        $image->save();

        event(new ImageResized($image, $resizedImages));
    }

    public function deleteImage($file)
    {
        $this->storageTmp->delete($file);
    }

    public function deleteCopy($file)
    {
        $this->storage->delete($file);
    }
}
