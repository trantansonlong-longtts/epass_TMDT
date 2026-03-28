<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('is_admin_authenticated', false)) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Vui long dang nhap de truy cap khu quan tri.');
        }

        return $next($request);
    }
}
