<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Before middleware

        Log::info('request', [
            'headers' => $request->headers->all(),
            'content' => $request->getContent(),
        ]);

        $response = $next($request);

        // After middleware

        Log::info('response', [
            'headers' => $response->headers,
            'content' => $response->getContent(),
        ]);

        return $response;
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @return void
     */
    public function terminate($request, $response)
    {
        Log::info('request-response', [
            'request' => [
                'headers' => $request->headers->all(),
                'content' => $request->getContent(),
            ],
            'response' => [
                'headers' => $response->headers,
                'content' => $response->getContent(),
            ],
        ]);
    }
}
