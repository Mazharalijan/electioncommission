<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class IfLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Session::has('user_id')) {
            return $next($request);
        } else {
            if (Session::has('user_id') && Session::get('role') == 'Admin') {
                return redirect()->route('admin.home');
            } elseif (Session::has('user_id') && Session::get('role') == 'Operator' && Session::get('otpstatus') == 'okay') {
                return redirect()->route('votes.pklist');
            } else {
                return redirect()->route('login');
            }
        }

    }
}
