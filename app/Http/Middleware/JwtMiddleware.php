<?php

namespace App\Http\Middleware;

use App\Commons\Responses;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response(Responses::tokenInvalid(), 401);
            } else if ($e instanceof TokenExpiredException) {
                $oldToken = JWTAuth::getToken();
                try {
                    $token = JWTAuth::setToken(JWTAuth::refresh());
                    $user = JWTAuth::authenticate();
                    $data = ['refresh_token' => JWTAuth::getToken()->get()];
                    return response(Responses::error('token expire', JWTAuth::getToken()->get()), 401);
                } catch (Exception $e) {
                    return response(Responses::tokenExpire(), 401);
                }
            } else {
                return response(Responses::tokenNotProvide(), 401);
            }
        }
        return $next($request);
    }
}
