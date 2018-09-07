<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Liyu\Signature\Facade\Signature;

class VerifyApiSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            if (config('signature.debug')) {
                if (!Signature::verify($request->sign)) {
                    throw new AuthenticationException;
                }
            }
        } catch (AuthenticationException $e) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
