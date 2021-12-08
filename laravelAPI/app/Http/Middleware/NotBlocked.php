<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->blocked == 0) {
            return $next($request);
        }
        abort(403, 'This user account is blocked');
    }
}
