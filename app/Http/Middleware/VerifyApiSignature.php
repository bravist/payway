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
                $signSecretType = sprintf('signature.%s.options.key', $request->sign_type);
                logger($signSecretType, [':sign_secret_type']);
                logger($request->sign, [':signature']);
                logger($request->except(['sign', 'sign_type']), [':sign_data']);
                if (!Signature::setKey(config($signSecretType))
                    ->verify($request->sign, $request->except(['sign', 'sign_type']))) {
                    throw new AuthenticationException;
                }
            }
        } catch (AuthenticationException $e) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
