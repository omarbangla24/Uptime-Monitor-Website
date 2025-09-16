<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class HandleImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('impersonate_user_id')) {
            $user = User::find(session('impersonate_user_id'));
            if ($user) {
                Auth::setUser($user);
            }
        }

        return $next($request);
    }
}
