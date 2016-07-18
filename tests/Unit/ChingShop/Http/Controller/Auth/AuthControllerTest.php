<?php

namespace Testing\Unit\ChingShop\Http\Controller\Auth;

use ChingShop\Modules\User\Http\Controllers\Auth\AuthController;
use Testing\Unit\UnitTest;

class AuthControllerTest extends UnitTest
{
    /** @var AuthController */
    private $authController;

    /**
     * Set up auth controller for each test.
     */
    public function setUp()
    {
        $this->authController = new AuthController();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            \ChingShop\Modules\User\Http\Controllers\Auth\AuthController::class, $this->authController);
    }
}
