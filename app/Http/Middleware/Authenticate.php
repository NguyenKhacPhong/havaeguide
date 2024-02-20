<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next,  ...$guards)
    {
        if (!Auth::check()) {// Chưa đăng nhập
            return redirect()->route('login');
        } else {
            $user = Auth::user();  //Lấy thông tin user khi đã đăng nhập
            return $next($request);
            //Kiểm tra quyền của người dùng
            // $route = $request->route()->getName();
            // if ($user->can($route)) {
            //     return $next($request);
            // } else {
            //     if($user->can('admin.dashboard')){
            //         return redirect()->route('admin.dashboard')->with(['danger' => 'Bạn không có quyền truy cập vào trang đó!']); // Thêm thông báo lỗi
            //     }else{
            //         Auth::logout(); // Logout nếu không có quyền
            //         return redirect()->route('login')->with(['danger' => 'Bạn không có quyền truy cập vào trang quản trị! Hãy liên hệ với quản trị viên để được cấp quyền!']); // Thêm thông báo lỗi
            //     }
                
                
            // }
        }
    }
}
