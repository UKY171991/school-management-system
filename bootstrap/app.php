<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: '/admin/dashboard'
        );

        $middleware->alias([
            'master.admin' => \App\Http\Middleware\MasterAdminMiddleware::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'api.domain' => \App\Http\Middleware\ApiDomainMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
