<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'no-cache' => \App\Http\Middleware\NoCacheMiddleware::class,
        ]);
        
        // Apply SetLocale, TrackVisitor and NoCache middleware to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\NoCacheMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 404 - Page Not Found
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'দুঃখিত! আপনি যা খুঁজছেন তা পাওয়া যায়নি।'
                ], 404);
            }
            
            return redirect()->route('home')
                ->with('error', 'দুঃখিত! আপনি যে পেজটি খুঁজছেন সেটি বিদ্যমান নেই বা সরানো হয়েছে। আপনাকে হোমপেজে নিয়ে আসা হয়েছে।');
        });
        
        // Handle 403 - Access Denied
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'এই পেজে প্রবেশের অনুমতি আপনার নেই।'
                ], 403);
            }
            
            if (auth()->check()) {
                return redirect()->route('dashboard')
                    ->with('error', 'এই পেজে প্রবেশের অনুমতি আপনার নেই। শুধুমাত্র অনুমোদিত ব্যবহারকারীরা এই পেজে যেতে পারেন।');
            }
            
            return redirect()->route('login')
                ->with('error', 'এই পেজে প্রবেশ করতে হলে আপনাকে লগইন করতে হবে।');
        });
    })->create();
