<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $condition)
    {
        $roles = getRoles();
        
        $user = $request->user();

        $currentUserRole = $user->userRole->first()->name;
        switch ($condition) {
            case 'Admin':
                if (!in_array($currentUserRole, $roles)) {
                    return redirect()->route('page-not-found');
                }
                break;
            default:
                break;
        }
        return $next($request);
    }
}
