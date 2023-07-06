<?php

namespace App\Http\Middleware;

use App\Http\Traits\LocalResponse;

use Closure;
use Illuminate\Http\Request;

class IsTrainer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->type == 'trainer') {
            return $next($request);
        }
        return LocalResponse::returnError("not a trainer", 401);
    }
}
