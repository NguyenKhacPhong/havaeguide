<?php
namespace App\Repositories\Page;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Exception;
use Cache;

class PageRepository implements IPageRepository
{
    private Page $page;
    function __construct(Page $page)
    {
        $this->page = $page;
    }
    public function find($id){
        return $this->page->find($id);
    }
    public function getListPage()
    {
        return $this->page->paginate();
    }
    
    public function createpage(){
        return view("admin.page.create", compact("sectors"));
    }

    public function storepage($request){
        $request->validate(
            [
                'slug'=> 'required|string|max:200| unique:pages,slug',
                'name'=> 'required|string|max:200',
            ],
            [
                'required'=> ':attribute không được bỏ trống!',
                'max'=> ':attribute có độ dài lớn nhất :max ký tự!',
                'unique' => ':attribute không được để trống!'
            ],
            [
                'name'=> "Tên ngành",
                'slug'=> "Đường dẫn tĩnh",
            ]
        );
        // dd($request->all());
        try{
            $page = $request->except('_token');
            return $this->page->create($page);
        }
        catch(Exception $e){
            return null;
        }
    }

    public function updatePage($request, $id){
        try{
            $page = $request->except('_token');
            $oldPage = $this->page->find($id);
            if($oldPage){
                return $oldPage->update($page);
            }
            return null;
        }
        catch(Exception $e){
            return null;
        }
    }
    
    public function deletepage($id){
        try{
            $page = $this->page->find($id);
            if($page != null){
                $page->delete();
                return redirect()->back()->with('success', "Xóa trang thành công!");
            }
            return redirect()->back()->with('danger', "Không có trang nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Xóa trang thất bại!");
        }
    }
    public function count()
    {
        $count = [];
        $count['all_page'] = $this->page->withTrashed()->count();
        $count['page_active'] = $this->page->all()->count();
        $count['page_hide'] = $this->page->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }

    //API

    public function getPage($slug){
        $page = Cache::get($slug);
        if($page == null){
            $page = page::where('slug', $slug)->select('slug', 'name', 'content')->first();
            Cache::put($slug ,$page, 86400);
        }
        return $page;
    }
}