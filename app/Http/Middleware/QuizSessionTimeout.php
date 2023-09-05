<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $quizSession = $request->quizSession;

        if ($quizSession->isTimeout()) {
            return redirect()->route('quiz_sessions.timeout', $quizSession->id);
        }

        return $next($request);
    }
}
