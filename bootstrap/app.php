<?php

use App\Console\Commands\MakeUserAdmin;
use App\Enums\Role;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            Role::ADMIN->value => EnsureUserIsAdmin::class,
        ]);
        $middleware->redirectUsersTo(static function (Request $request): string {
            $user = $request->user();

            if ($user instanceof User && $user->isAdmin()) {
                return route('admin.report.index');
            }

            return route('home');
        });
        $middleware->redirectGuestsTo(static fn (Request $request): string => route('login.create')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withCommands([
        MakeUserAdmin::class,
    ])
    ->create();
