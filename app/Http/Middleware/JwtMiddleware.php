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
      return response()->json(['error' => $e->getMessage()], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      return response()->json(['error' => $e->getMessage()], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json(['error' => $e->getMessage()], 401);
    }
    return $next($request);
  }
}
