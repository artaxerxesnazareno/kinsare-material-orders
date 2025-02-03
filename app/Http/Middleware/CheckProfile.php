<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfile
{
    public function handle(Request $request, Closure $next, string $profile): Response
    {
        if (!Auth::check() || Auth::user()->profile !== $profile) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
