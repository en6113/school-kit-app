<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // ガードで「ログイン済みか」をチェックする
            if (Auth::guard($guard)->check()) {

                // 業者としてログイン済みの場合
                if ($guard === 'vendor') {
                    return redirect()->route('vendor.products.index');
                }

                // 一般ユーザーとしてログイン済みの場合
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // ログインしていなければ、そのままリクエストを通す
        return $next($request);
    }
}
