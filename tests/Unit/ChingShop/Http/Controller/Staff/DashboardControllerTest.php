<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use Illuminate\View\View;

use ChingShop\Http\Controllers\Staff\DashboardController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class DashboardControllerTest extends ControllerTest
{
    /** @var DashboardController */
    private $dashboardController;

    /**
     * Set up dashboard controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->dashboardController = new DashboardController;
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            DashboardController::class,
            $this->dashboardController
        );
    }

    /**
     * Should be able to get the dashboard index.
     */
    public function testGetIndex()
    {
        $view = $this->dashboardController->getIndex();
        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * Should be able to get PHP info page.
     */
    public function testGetPhpInfo()
    {
        $this->expectOutputRegex('/PHP/');
        $this->dashboardController->getPhpInfo();
    }
}
