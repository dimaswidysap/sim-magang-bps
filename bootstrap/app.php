<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        $middleware->web(append: [
            EnsureUserIsActive::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login-form'));

        $middleware->redirectUsersTo(function (Request $request): string {
            return match ($request->user()?->role) {
                'admin' => route('admin-index'),
                'asn' => route('asn-index'),
                'mahasiswa' => route('mahasiswa-index'),
                default => route('landing-page'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
