<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;

use ChingShop\Http\Requests;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('staff.dashboard.index');
    }
}
