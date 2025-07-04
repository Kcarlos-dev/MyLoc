<?php

//namespace App\Http\Middleware;
//
//use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
//
//class VerifyCsrfToken extends Middleware
//{
//    /**
//     * The URIs that should be excluded from CSRF verification.
//     *
//     * @var array<int, string>
//     */
//    protected $except = [
//        //
//    ];
//}

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
