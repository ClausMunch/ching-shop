<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Imagick\ImagickContract;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

abstract class TestWithImagick extends UnitTest
{
    /** @var ImagickContract|MockObject */
    private $imagick;

    /**
     * @return ImagickContract|MockObject
     */
    protected function imagick(): MockObject
    {
        if (empty($this->imagick)) {
            $this->imagick = $this->makeMock(ImagickContract::class);
            $this->imagick->expects($this->any())
                ->method('getImage')
                ->willReturn($this->imagick);
        }

        return $this->imagick;
    }

    /**
     * Unset imagick mock after each test.
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->imagick);
    }
}
