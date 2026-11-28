<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCouncilStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_council_staff, 403);

        return $next($request);
    }
}
