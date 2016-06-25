<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;

class DashboardController extends Controller
{
    /** @var WebUi */
    private $webUi;

    /**
     * DashboardController constructor.
     *
     * @param WebUi $webUi
     */
    public function __construct(WebUi $webUi)
    {
        $this->webUi = $webUi;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return $this->webUi->view('staff.dashboard.index');
    }

    /**
     * Display PHP info output.
     */
    public function getPhpInfo()
    {
        phpinfo();
    }
}
