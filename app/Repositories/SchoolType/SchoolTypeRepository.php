<?php
namespace App\Repositories\SchoolType;

use App\Models\SchoolType;
use App\Repositories\Repositories;
use Exception;
use Cache;

class SchoolTypeRepository extends Repositories implements ISchoolTypeRepository
{
    private $SchoolType;
    function __construct(SchoolType $SchoolType)
    {
        $this->SchoolType = $SchoolType;
    }

    public function getSchoolType($request, $status)
    {
        $SchoolTypes = $this->getModelWithStatus($status, $this->SchoolType);
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $SchoolTypes = $SchoolTypes->where('SchoolTypeType_name', 'like', "%{$search}%");
        }
        $SchoolTypes = $SchoolTypes->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);
        
        return view('admin.school_type.index', compact("SchoolTypes", "count", "list_act"));
    }
    public function storeSchoolType($request){
        $request->validate(
            [
                "type_name" => "required|string|max:200",
                'image' => 'mimes:jpg,png,gif,webp|max:20000'
            ],
            [
                "required" => ":attribute không được để trống!",
                "string" => ":attribute phải là một chuỗi!",
                "max" => ":attribute không vượi quá 200 ký tự!",
                "mimes" => ":attribute phải có định dạng jpg,png,gif,webp"
            ],
            [
                "type_name" => "Tên hệ đạo",
                "image" => "Hình ảnh"
            ]
        );
        try{
            $this->SchoolType->create([
                "type_name" => $request->type_name,
                "description" => $request->description,
                "image" => $request->image,
            ]);
            return redirect("admin/school_type")->with('success', "Đã thêm hệ đào tạo thành công!");
        }catch(Exception){
            return redirect("admin/school_type")->with('danger', "Thêm hệ đạo tạo thất bại!");
        }
    }

    public function updateSchoolType($request){
        $request->validate(
            [
                "type_name" => "required|string|max:200"
            ],
            [
                "required" => ":attribute không được để trống!",
                "string" => ":attribute phải là một chuỗi!",
                "max" => ":attribute không vượi quá 200 ký tự!"
            ],
            [
                "type_name" => "Tên hệ đạo"
            ]
        );
        try{
            $id = $request->id;
            $data = $request->only('type_name');
            $this->SchoolType->find($request->id)->update($data);
            return redirect("admin/school_type")->with('success', "Đã cập nhật hệ đào tạo thành công!");
        }catch(Exception){
            return redirect("admin/school_type")->with('success', "Cập nhật hệ đào tạo thất bại!");
        }
    }
    public function editSchoolType($id){;
        $type = $this->SchoolType->find($id)->first();
        return response()->json($type);
    }

    public function removeSchoolType($id){
        try{
            $this->SchoolType->find($id)->delete();
            return redirect("admin/school_type")->with('success', "Ẩn hệ đào tạo thành công!");
        }catch(Exception){
            return redirect("admin/school_type")->with('success', "Ẩn hệ đào tạo thất bại!");
        }
    }

    public function restoreSchoolType($id){
        try{
            $type = $this->SchoolType->withTrashed()->find($id);
            if($type != null){
                $type->restore();
                return redirect("admin/school_type")->with('success', "Hiện thị hệ đào tạo thành công!");
            }
            return redirect("admin/school_type")->with('danger', "Không có hệ đào tạo nào như thế!");
        }catch(Exception){
            return redirect("admin/school_type")->with('success', "Hiển thị hệ đào tạo thất bại!");
        }
    }

    public function deleteSchoolType($id){
        try{
            $this->SchoolType->find($id)->forceDelete();
            return redirect("admin/school_type")->with('success', "Xóa hệ đào tạo thành công!");
        }catch(Exception){
            return redirect("admin/school_type")->with('success', "Xóa hệ đào tạo thất bại!");
        }
    }
    public function count()
    {
        $count = [];
        $count['all_Type'] = $this->SchoolType->withTrashed()->count();
        $count['Type_active'] = $this->SchoolType->all()->count();
        $count['Type_hide'] = $this->SchoolType->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }

    //API
    public function getAllType(){
        $types = Cache::get('types');
        if($types == null){
            $types = SchoolType::select('id', 'type_name')->get();
            Cache::put('types',$types, 86400);
        }
        return $types;
    }
}