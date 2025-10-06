<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresHqAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (!$user->isHqAdmin() && !$user->isSuperAdmin())) {
            abort(403, 'This action requires HQ Admin or Super Admin privileges.');
        }

        return $next($request);
    }
}
