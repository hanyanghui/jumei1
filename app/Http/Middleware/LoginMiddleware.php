<?php

namespace App\Http\Middleware;

use Closure;

class LoginMiddleware
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
        echo '登录检测';
        // 判断session中是否存在登录用户
        if(false){
            // 请求传递下层控制器执行业务逻辑
             return $next($request);
        }else{
            // 跳转到登录页面
            return redirect('/login');
        }
       
    }
}
