<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.session' => \App\Http\Middleware\AdminSessionCheck::class,
            'admin.key' => \App\Http\Middleware\AdminKeyCheck::class,
            'check.user' => \App\Http\Middleware\CheckUserAccess::class,
        ]);

        $middleware->web(\App\Http\Middleware\AdminSessionCheck::class);
        $middleware->append(\App\Http\Middleware\AutoStorageSync::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

return $app;
