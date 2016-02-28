<?php

namespace Testing\Unit\ChingShop\Http\Controller\Auth;

use Testing\Unit\UnitTest;
use ChingShop\Http\Controllers\Auth\PasswordController;

class PasswordControllerTest extends UnitTest
{
    /** @var PasswordController */
    private $passwordController;

    /**
     * Set up password controller for each test
     */
    public function setUp()
    {
        $this->passwordController = new PasswordController;
    }

    /**
     * Sanity check for instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            PasswordController::class,
            $this->passwordController
        );
    }
}
