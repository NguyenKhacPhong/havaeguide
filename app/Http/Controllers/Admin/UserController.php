<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\IUserRepository;

class UserController extends Controller
{
    //
    protected $userRepository;
    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware(function($request, $next){
            session(['module_active' => 'user']);
            return $next($request);
        });
    }

    function index(Request $request, $status=""){
        $count = $this->userRepository->count();
        if($status == "del"){
            $list_act = [
                'restore'=>"Khôi phục",
                'delete'=>"Xóa vĩnh viễn"
            ];
            $search = "";
            if($request->input('keyword'))
                $search = $request->input('keyword');
            $users = $this->userRepository->getUserRemove($search);
            //dd($users->total());
            return view("admin.user.list", compact("users", "count", "list_act"));
        }else if($status == "active"){
            $list_act = [
                'remove'=>"Vô hiệu hóa",
            ];
            $search = "";
            if($request->input('keyword'))
                $search = $request->input('keyword');
            $users = $this->userRepository->getUserActive($search);
            // dd($users->total());
            return view("admin.user.list", compact("users", "count", "list_act"));
        }else{
            if($count['user_remove'] != 0){
                $list_act = [
                    'restore'=>"Khôi phục",
                    'remove'=>"Vô hiệu hóa",
                    'delete'=>"Xóa vĩnh viễn"
                ];
            }else{
                $list_act = [
                    'remove'=>"Vô hiệu hóa",
                ];
            }
            $search = "";
            if($request->input('keyword'))
                $search = $request->input('keyword');
            $users = $this->userRepository->getAllUser($search);
            //dd($users->total());
            return view("admin.user.list", compact("users", "count", "list_act"));
        }
    }

    function create(){
        $roles = Roles::get();
        return view('admin.user.create', compact('roles'));
    }

    function store(Request $request){
        $this->userRepository->create($request);
        return redirect('admin/user/list')->with('success', 'Đã thêm một người dùng mới!', 'alert', 'success');
    }

    //Xóa hoàn toàn một user khỏi hệ thống
    function delete($id){
        if($this->userRepository->delete($id)){
            return redirect('admin/user/list')->with('success', "Bạn đã xóa vĩnh viễn thành viên!");
        }else{
            return redirect('admin/user/list')->with('danger', "Bạn không thể xóa tài khoản này!");
        }
    }

    //Vô hiệu hóa một user
    function remove($id){
        if($this->userRepository->remove($id)){
            return redirect('admin/user/list')->with('success', "Bạn đã vô hiệu hóa tài khoản thành công!");
        }else{
            return redirect('admin/user/list')->with('danger', "Bạn không thể vô hiệu hóa tài khoản đó!");
        }
    }

    //Khôi phục một user bị vô hiệu hóa
    function restore($id){
        if($this->userRepository->restore($id)){
            return redirect('admin/user/list')->with('success', "Bạn đã khôi phúc tài khoản thành công!");
        }else{
            return redirect('admin/user/list')->with('danger', "Bạn không thể khôi phục tài khoản đó!");
        }
    }

    //Hành động áp dụng hàng loạt
    function action(Request $req){
        $listcheck = $req->input('list_check');

        if($listcheck){
            //Loại bỏ thao tác lên chính tài khoản của mình
            foreach($listcheck as $k => $id){
                if(Auth::id() == $id){
                    unset($listcheck[$k]);
                }
            }
            if(!empty($listcheck)){

                $act = $req->input('act');
                //Thực hiện hành động vô hiệu hoa các tài khoản có id trong list_check
                if($act == "remove"){
                    User::destroy($listcheck);
                    return redirect('admin/user/list')->with('success', "Bạn đã vô hiệu hóa thành công!",);
                }
                //Thực hiện hành động khôi phục các tài khoản có id trong list_check
                if($act == 'restore'){
                    User::withTrashed()
                    ->whereIn('id', $listcheck)
                    ->restore();
                    return redirect('admin/user/list')->with('success', "Bạn đã khôi phục thành công!");
                }
                if($act == 'delete'){
                    User::withTrashed()
                    ->whereIn('id', $listcheck)
                    ->forceDelete();
                    return redirect('admin/user/list')->with('success', "Bạn đã xóa vĩnh viễn thành viên!", 'alert', 'success');
                }
            }
        }
    }

    function edit($id){
        $roles = Roles::leftJoin('user_roles', 'roles.id','=', 'user_roles.role_id')
        ->select('roles.id', 'roles.name', 'user_roles.user_id')->get();
        $user = $this->userRepository->find($id);
        return view('admin.user.edit', compact('user','roles'));
    }
    function update(Request $req, $id){
        if($this->userRepository->updateUser($id,$req)){
            return redirect('admin/user/list')->with('success', "Đã cập nhật thành công!");
        }else{
            return redirect('admin/user/list')->with('success', "Đã cập nhật thành công!");
        }
    }

}
