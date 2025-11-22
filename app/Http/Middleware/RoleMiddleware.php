<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        if ($role === 'mentor' && !$user->isMentor()) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        if ($role === 'mahasiswa' && !$user->isMahasiswa()) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}