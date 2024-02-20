<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Page\IPageRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private IPageRepository $pageRepo;
    public function __construct(IPageRepository $pageRepo){
        $this->pageRepo = $pageRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'page']);
            return $next($request);
        });
    }
    public function index(){
        $pages = $this->pageRepo->getListPage();
        return view("admin.page.index", compact("pages"));
    }
    public function create(){
        return view("admin.page.create");
    }

    public function store(Request $request){
        if($this->pageRepo->storePage($request) != null){
            return redirect()->back()->with("success", "Thêm ngành đào tạo thành công!");
        }else{
            return redirect()->back()->with("danger", "Thêm ngành đào tạo thất bại!");
        }
    }

    public function edit($id){
        $page = $this->pageRepo->find($id);
        return view("admin.page.edit", compact("page"));
    }

    public function update(Request $request, $id){
        if($this->pageRepo->updatePage($request, $id) != null){
            return redirect()->back()->with("success", "Cập nhật trang thành công!");
        }else{
            return redirect()->back()->with("danger", "Cập nhật trang thất bại!");
        }
    }
    public function delete($id){
        return $this->pageRepo->deletePage($id);
    }
}
