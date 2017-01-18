<?php

namespace App\Http\Middleware;
 
use Closure;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取url中的路由规则
        $route = $request->path();
        // 存储所有的请求路由规则 
        file_put_contents('./luyou.txt',$route."\n\r",FILE_APPEND);
        // echo '中间件';
        return $next($request);
    }
}
