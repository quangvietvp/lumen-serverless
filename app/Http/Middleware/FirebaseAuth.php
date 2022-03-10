<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuth
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $idTokenString = $request->bearerToken();
        try {
            if ($idTokenString != '') {
                $this->auth->verifyIdToken($idTokenString);
                return $next($request);
            } else {
                return response()->json(['error' => 'Token is invalid']);
            }
        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
