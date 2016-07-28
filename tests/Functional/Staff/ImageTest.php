<?php

namespace Testing\Functional\Staff;

use Testing\Functional\FunctionalTest;

class ImageTest extends FunctionalTest
{
    use StaffUser;

    /**
     * Should be able to visit the staff images index.
     *
     * @slowThreshold 800
     */
    public function testCanVisitImageIndex()
    {
        $imageIndex = route('catalogue.staff.products.images.index');
        $this->actingAs($this->staffUser())
            ->visit($imageIndex)
            ->seePageIs($imageIndex)
            ->assertResponseOk();
    }
}
