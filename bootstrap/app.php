<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->web(append: [
            SetLocale::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $exception) {
            //     if ($exception->getStatusCode() == 400) {
            //         return response()->view("errors.400", [], 400);
            //     }

            if ($exception->getStatusCode() == 403) {
                return response()->view("errors.403", [], 403);
            }

            if ($exception->getStatusCode() == 404) {
                return response()->view("errors.404", [], 404);
            }

            //     if ($exception->getStatusCode() == 500) {
            //         return response()->view("errors.500", [], 500);
            //     }

            //     if ($exception->getStatusCode() == 503) {
            //         return response()->view("errors.503", [], 503);
            //     }
        });
    })->create();
