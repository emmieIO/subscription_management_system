<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpWhitelist
{
    public function handle(Request $request, Closure $next): Response
    {
        $ipList = [
            '127.0.0.1',
            '52.31.139.75',
            '52.49.173.169',
            '52.214.14.220',
        ];
        $clientIp = $request->ip();

        if (!in_array($clientIp, $ipList)) {
            return response()->response_error('Forbidden', 403);
        }
        return $next($request);
    }
}
