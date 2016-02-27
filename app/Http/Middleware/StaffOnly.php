<?php

namespace ChingShop\Http\Middleware;

use ChingShop\Http\View\Staff\LocationComposer;
use ChingShop\User\User;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOnly
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $requestUser */
        $requestUser = $request->user();
        if (!$requestUser) {
            return $this->deny($request);
        }

        if (!$requestUser->isStaff()) {
            return $this->deny($request);
        }

        view()->composer('*', LocationComposer::class);

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseFactory
     */
    private function deny(Request $request)
    {
        if ($request->ajax()) {
            return response('Unauthorised', Response::HTTP_UNAUTHORIZED);
        } else {
            return redirect()->guest('auth/login');
        }
    }
}
