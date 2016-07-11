<?php

namespace ChingShop\Events;

use ChingShop\Image\Image;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewImageEvent
 *
 * @package ChingShop\Events
 */
class NewImageEvent extends Event
{
    use SerializesModels;

    /** @var Image */
    private $image;

    /**
     * Create a new event instance.
     *
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function image(): Image
    {
        return $this->image;
    }
}
