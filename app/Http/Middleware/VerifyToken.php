<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SessionUser;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $checkToken = SessionUser::where('token', $request->header('token'))->first();
        if(empty($request->header('token'))){
            return response()->json([
                'status'=>401,
                'message'=>'Bạn không gửi token lên!'
            ],401);
        }elseif(empty($checkToken)){
            return response()->json([
                'status'=>401,
                'message'=>'Token không hợp lệ!'
            ],401);
        }
        return $next($request);
    }
}




