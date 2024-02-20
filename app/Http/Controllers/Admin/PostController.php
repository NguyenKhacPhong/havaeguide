<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Post\IPostRepository;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private IPostRepository $postRepo;
    public function __construct(IPostRepository $postRepo){
        $this->postRepo = $postRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    public function index(Request $request, $status = '')
    {
        $compact = $this->postRepo->getListPost($request, $status);

        return view('admin.post.index', $compact);
    }
    public function create(){
        return view('admin.post.create');
    }
    public function store(Request $request){
        try{
            $post = $this->postRepo->storePost($request);
            if($post != null){
                return redirect()->route('admin.post.index')->with('success', 'Thêm bài viết thành công!');
            }else{
                return redirect()->route('admin.post.index')->with('danger', 'Thêm bài viết không thành công!');
            }
        }catch(Exception $ex){
            return redirect()->route('admin.post.index')->with('danger', 'Lỗi trong quá trình thêm bài viết!'.$ex);
        }
    }
    public function edit($id){
        try{
            $post = $this->postRepo->find($id);
            return view('admin.post.edit', compact('post'));
            
        }catch(Exception $ex){
            redirect()->route('admin.post.index')->with('danger', 'Có lỗi xảy ra!'.$ex);
        }
    }
    public function update($id,Request $request)
    {
        try{
            $post = $this->postRepo->updatePost($request, $id);
            if($post != null){
                return redirect()->route('admin.post.index')->with('success', 'Cập nhật bài viết thành công!');
            }else{
                return redirect()->route('admin.post.index')->with('danger', 'Cập nhật bài viết không thành công!');
            }
        }catch(Exception $ex)
        {
            redirect()->route('admin.post.index')->with('danger', 'Có lỗi xảy ra trong quá trình cập nhật!'.$ex);
        }
    }
    public function remove($id){
        try{
            $post = $this->postRepo->removePost($id);
            if($post != null){
                return redirect()->route('admin.post.index')->with('success', 'Ẩn bài viết thành công!');
            }else{
                return redirect()->route('admin.post.index')->with('danger', 'Ẩn bài viết không thành công!');
            }
        }catch(Exception $ex)
        {
            redirect()->route('admin.post.index')->with('danger', 'Có lỗi xảy ra trong quá trình Ẩn!'.$ex);
        }
    }

    public function restore($id){
        try{
            $post = $this->postRepo->restorePost($id);
            if($post != null){
                return redirect()->route('admin.post.index')->with('success', 'Hiển thị bài viết thành công!');
            }else{
                return redirect()->route('admin.post.index')->with('danger', 'Hiển thị bài viết không thành công!');
            }
        }catch(Exception $ex)
        {
            redirect()->route('admin.post.index')->with('danger', 'Có lỗi xảy ra trong quá trình hiển thị!'.$ex);
        }
    }
    public function delete($id){
        try{
            $post = $this->postRepo->deletePost($id);
            if($post != null){
                return redirect()->route('admin.post.index')->with('success', 'Xóa bài viết thành công!');
            }else{
                return redirect()->route('admin.post.index')->with('danger', 'Xóa bài viết không thành công!');
            }
        }catch(Exception $ex)
        {
            redirect()->route('admin.post.index')->with('danger', 'Có lỗi xảy ra trong quá trình xóa!'.$ex);
        }
    }
}
