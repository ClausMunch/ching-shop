<?php

namespace ChingShop\Http\Controllers\Auth;

use ChingShop\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
}
