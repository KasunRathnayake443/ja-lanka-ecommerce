<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DetectMobile;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Essential for Render: Trust all upstream proxies to ensure correct HTTPS generation
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'guest' => RedirectIfAuthenticated::class,
            'guest.admin' => RedirectIfNotAdmin::class,
            'detect.mobile' => \App\Http\Middleware\DetectMobile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();