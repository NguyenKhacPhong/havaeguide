<?php

namespace App\Http\Controllers\Admin;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function  __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'role']);
            return $next($request);
        });
    }

    public function index()
    {
        $data = Roles::paginate(15);
        return view('admin.role.index', compact('data'));
    }

    public function create()
    {
        $routes = [];
        $allRoute = Route::getRoutes();
        foreach ($allRoute as $route){
            $name = $route->getName();
            if(!in_array($name, $routes) && strpos($name,'admin') !== false)
                array_push($routes,$name);
        }
//        dd($routes);
        return view('admin.role.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ],
            [
                'name.required' => "Tên nhóm quyền không được để trống"
            ]
        );
        $routes = json_encode($request->route);
        Roles::create(
            ['name' => $request->name,
            'permissions' => $routes
            ]
        );
        return redirect('admin/role')->with('success', "Thêm nhóm quyền thành công!");
    }
    public function listRoute()
    {
        $routeCollection = Route::getRoutes();
        $routeNames = [];

        foreach ($routeCollection as $value) {
            if(strpos($value->getName(), '') !== false){
                $routeNames[] = $value->getName();
            }
        }
        return response()->json(
            $routeNames
        );
    }
}
