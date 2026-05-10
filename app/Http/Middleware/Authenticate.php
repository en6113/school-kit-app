<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // URLに 'vendor' と付いていたら、業者用ログイン画面へ
            if ($request->is('vendor*')) {
                return route('vendor.login');
            }
            return route('login');
        }
    }
}
