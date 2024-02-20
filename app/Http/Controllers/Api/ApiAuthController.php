<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\IUserRepository;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    protected $userRepo;

    function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(Request $request)
    {

        $response = $this->userRepo->register($request);
        return response()->json(
            $response
        , $response['code']);
    }

    public function login(Request $request)
    {
        return $this->userRepo->login($request);
    }
    public function logout(Request $request){
        return $this->userRepo->logout($request);
    }
    public function getUserInfo(Request $request){
        return $this->userRepo->getUserInfo($request);
    }

}
