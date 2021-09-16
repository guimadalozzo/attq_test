<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->getUser();
        $pass = $request->getPassword();

        if (!$user || !$pass) {
            return response()->json(['error' => 'without authorization'], 401);
        }

        if (!Auth::attempt(['email' => $user, 'password' => $pass]))
        {
            return response()->json(['error' => 'User not found'], 401);
        }

        return $next($request);
    }
}
