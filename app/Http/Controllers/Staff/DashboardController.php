<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;

/**
 * Class DashboardController.
 */
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
     *
     * @return string
     */
    public function getPhpInfo()
    {
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_get_clean();

        return $this->webUi->view('staff.dashboard.info', compact('info'));
    }
}
