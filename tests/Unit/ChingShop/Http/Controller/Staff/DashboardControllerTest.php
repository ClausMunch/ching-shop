<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Http\Controllers\Staff\DashboardController;
use Illuminate\View\View;
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

        $this->dashboardController = new DashboardController($this->webUi());
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
        $this->webUi()->expects($this->atLeastOnce())->method('view');

        $this->dashboardController->getIndex();
    }
}
