<?php

namespace Testing\Functional\Staff;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

class ImageTest extends FunctionalTest
{
    use StaffUser, CreateCatalogue;

    /**
     * Should be able to visit the staff images index.
     *
     * @slowThreshold 800
     */
    public function testCanVisitImageIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit($this->imageIndex())
            ->seePageIs($this->imageIndex())
            ->assertResponseOk();
    }

    /**
     * Should be able to set the alt text for an image.
     */
    public function testCanSetImageAltText()
    {
        // Given there is an image;
        $image = $this->createImage();

        // When we view it in the image index;
        $this->actingAs($this->staffUser())
            ->visit($this->imageIndex())
            ->see($image->id)
            ->see($image->alt_text);

        // Then we should be able to set its alt text.
        $newAltText = str_random();
        $this->submitForm(
            "Save alt text {$image->id}",
            ['alt-text' => $newAltText]
        );

        $this->seePageIs($this->imageIndex());
        $this->see($newAltText);
    }

    /**
     * @return string
     */
    private function imageIndex(): string
    {
        return route('catalogue.staff.products.images.index');
    }
}
