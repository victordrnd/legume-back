<?php

namespace App\Http\Middleware;

use JWTAuth;
use Closure;
use Exception;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class JwtMiddleware
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
      $user = JWTAuth::parseToken()->authenticate();
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      return Controller::responseJson(401, $e->getMessage());
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      return Controller::responseJson(401, $e->getMessage());
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      return Controller::responseJson(401, $e->getMessage());
    }
    return $next($request);
  }
}
