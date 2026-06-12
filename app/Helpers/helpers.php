<?php

if (!function_exists('isMobile')) {
    function isMobile()
    {
        return preg_match('/(android|iphone|ipad|mobile|blackberry|iemobile|opera mini|windows phone)/i', request()->header('User-Agent'));
    }
}

if (!function_exists('getDashboardRoute')) {
    function getDashboardRoute()
    {
        if (isMobile()) {
            return route('mobile.account.dashboard');
        }
        return route('account.dashboard');
    }
}

if (!function_exists('getHomeRoute')) {
    function getHomeRoute()
    {
        if (isMobile()) {
            return route('mobile.home');
        }
        return route('home');
    }
}