<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $segment = $request->segment(1);
        if ($segment === 'en') {
            app()->setLocale('en_US');
        } elseif (session()->has('locale')) {
            $sessLocale = session('locale');
            app()->setLocale($sessLocale === 'en' ? 'en_US' : $sessLocale);
        } else {
            app()->setLocale('vi');
        }

        return $next($request);
    }
}

