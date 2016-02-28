<?php

namespace Testing\Unit\ChingShop\Http\Controller\Auth;

use ChingShop\Http\Controllers\Auth\PasswordController;
use Testing\Unit\UnitTest;

class PasswordControllerTest extends UnitTest
{
    /** @var PasswordController */
    private $passwordController;

    /**
     * Set up password controller for each test.
     */
    public function setUp()
    {
        $this->passwordController = new PasswordController();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            PasswordController::class,
            $this->passwordController
        );
    }
}
