<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectMobile
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is on mobile device
        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|ipod|mobile|blackberry|iemobile|opera mini|windows phone)/i', $userAgent);
        
        // Don't redirect if already on mobile route
        $isMobileRoute = str_starts_with($request->path(), 'mobile');
        
        // Don't redirect if user explicitly wants desktop
        $forceDesktop = session()->get('force_desktop', false);
        
        // Don't redirect admin routes
        $isAdminRoute = str_starts_with($request->path(), 'admin');
        
        // Don't redirect API routes
        $isApiRoute = str_starts_with($request->path(), 'api');
        
        if ($isMobile && !$isMobileRoute && !$forceDesktop && !$isAdminRoute && !$isApiRoute) {
            // Store intended URL
            session()->put('mobile.intended', $request->fullUrl());
            return redirect()->route('mobile.home');
        }
        
        return $next($request);
    }
}