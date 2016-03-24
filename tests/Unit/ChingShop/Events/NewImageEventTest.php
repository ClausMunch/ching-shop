<?php

namespace Testing\Unit\ChingShop\Events;

use ChingShop\Events\NewImageEvent;
use ChingShop\Image\Image;
use Testing\Unit\UnitTest;

class NewImageEventTest extends UnitTest
{
    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $event = new NewImageEvent(new Image);
        $this->assertInstanceOf(NewImageEvent::class, $event);
    }

    /**
     * Should keep the image id.
     */
    public function testImage()
    {
        $image = new Image;
        $event = new NewImageEvent($image);
        $this->assertSame($image->id, $event->image()->id);
    }
}
