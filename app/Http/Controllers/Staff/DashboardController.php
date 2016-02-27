<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('staff.dashboard.index');
    }

    /**
     * Display PHP info output.
     */
    public function getPhpInfo()
    {
        phpinfo();
    }
}
