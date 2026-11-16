<?php
namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;

class BasicAuthMiddleware
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        $user = $request->getUser();
        $pass = $request->getPassword();

        if ($user !== env('BASIC_AUTH_USER') || $pass !== env('BASIC_AUTH_PASS')) {
            return $this->respond(['status' => false, 'message' => 'Unauthorized'], 401)
                        ->header('WWW-Authenticate', 'Basic realm="Coffee Shop API"');
        }

        return $next($request);
    }
}