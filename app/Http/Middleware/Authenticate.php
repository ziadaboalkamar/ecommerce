<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Request;
class Authenticate extends Middleware
{

    protected function redirectTo( $request)
    {
        if (!$request->expectsJson()) {
            if (Request::is('admin/*'))
                return route('get.admin.login');
            else
                return route('login');

        }
        }

}
