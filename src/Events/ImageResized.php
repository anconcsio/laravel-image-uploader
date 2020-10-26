<?php

namespace Eval4VictoryCTO\LaravelImageUploader\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ImageResized implements ShouldBroadcastNow
{
    public $message;
    public $images;

    protected $image;

    protected $notifyChannels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($image, $imageCopies)
    {
        $this->image = $image;

        $this->message = 'Image ' . htmlspecialchars($image->original_name) . ' was resized';
        $this->images = $imageCopies;
        $this->notifyChannels = config('image-uploader.notify-channels', '');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return $this->notifyChannels;
    }

    public function broadcastAs()
    {
        return config('image-uploader.notify-name', 'image-resized');
    }

    public function broadcastWhen()
    {
        return (boolean) $this->notifyChannels;
    }

    public function getImage()
    {
        return $this->image;
    }
}
