<?php
namespace App\Repositories\Major;

use App\Models\Major;
use App\Models\Sector;
use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Exception;
use Cache;

class MajorRepository extends Repositories implements IMajorRepository
{
    private $major;
    private $sector;
    function __construct(Major $major,Sector $Sector)
    {
        $this->major = $major;
        $this->sector = $Sector;
    }
    public function find($id){
        return $this->major->find($id);
    }
    public function getMajor($request, $status)
    {
        $majors = $this->getModelWithStatus($status, $this->major)->join('sectors', 'sectors.id', '=', 'majors.sector_id')->select('majors.*', 'sectors.name');
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $majors = $majors->where('major_name', 'like', "%{$search}%");
        }
        $majors = $majors->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.major.index', compact("majors", "count", "list_act"));
    }

    public function createMajor(){
        $sectors = $this->sector->select("id", "name")->get();
        return view("admin.major.create", compact("sectors"));
    }

    public function storeMajor($request){
        $request->validate(
            [
                'major_name'=> 'required|string|max:200',
            ],
            [
                'required'=> ':attribute không được bỏ trống!',
                'max'=> ':attribute có độ dài lớn nhất :max ký tự!',
            ],
            [
                'major_name'=> "Tên ngành",
            ]
        );
        // dd($request->all());
        try{
            $major = $request->except('_token');
            $this->major->create($major);
                return redirect()->back()->with("success", "Thêm ngành đào tạo thành công!");
        }catch(Exception $ex){
            return redirect()->back()->with("danger", "Thêm ngành đào tạo thất bại!".$ex->getMessage());
        }
    }

    public function editMajor($id){
        $Sectors = $this->sector->select("id", "name")->get();
        $major = $this->find($id);
        return compact("Sectors", "major");
    }
    public function updateMajor($request, $id){
        try {
            $major = $request->except('_token'); 
            $this->major->find($id)->update($major);

            return redirect()->back()->with("success", "Cập nhật thông tin ngành học thành công!");
        } catch(Exception $ex) {
            return redirect()->back()->with("danger", "Cập nhật thông tin ngành học thất bại! " . $ex->getMessage());
        }
    }

    public function removeMajor($id){
        try{
            $major = $this->major->withTrashed()->find($id);
            if($major != null){
                $major->delete();
                return redirect()->back()->with('success', "Ẩn ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Ẩn ngành đào tạo thất bại!");
        }
    }
    public function restoreMajor($id){
        try{
            $major = $this->major->withTrashed()->find($id);
            if($major != null){
                $major->restore();
                return redirect()->back()->with('success', "Hiển thị ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Hiển thị ngành đào tạo thất bại!");
        }
    }
    public function deleteMajor($id){
        try{
            $major = $this->major->withTrashed()->find($id);
            if($major != null){
                $major->forceDelete();
                return redirect()->back()->with('success', "Xóa ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Xóa ngành đào tạo thất bại!");
        }
    }
    public function count()
    {
        $count = [];
        $count['all_major'] = $this->major->withTrashed()->count();
        $count['major_active'] = $this->major->all()->count();
        $count['major_hide'] = $this->major->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }

    //API

    public function getAllMajor(){
        $majors = Cache::get('majors');
        if($majors == null){
            $majors = Major::select('id', 'major_name', 'sector_id')->get();
            Cache::put('majors',$majors, 86400);
        }
        return $majors;
    }

    public function getMajorInArray($arr){
        return $this->major->whereIn("id",$arr)->select("id", "major_name")->get();
    }
}
