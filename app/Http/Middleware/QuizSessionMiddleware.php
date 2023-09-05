<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$quizSession = cache()->remember(
                'quizSessions:' . auth()->user()->id,
                config('cache.expiration'),
                fn() => $request->user()->getLastQuizSession()
        )) {
            abort(404);
        }

        $request->merge(['quizSession' => $quizSession]);

        return $next($request);
    }
}
