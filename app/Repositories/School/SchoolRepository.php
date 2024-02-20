<?php

namespace App\Repositories\School;

use App\Models\School;
use App\Models\SchoolType;
use App\Models\Major;
use App\Models\Sector;
use App\Models\SchoolMajor;

use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Exception;


class SchoolRepository extends Repositories implements ISchoolRepository
{
    private $school;
    private $type;
    function __construct(School $school, SchoolType $type)
    {
        $this->school = $school;
        $this->type = $type;
    }
    public function find($id)
    {
        return $this->school->find($id);
    }
    public function getSchools($request, $status)
    {
        $schools = $this->getModelWithStatus($status, $this->school);
        if ($request->keyword) {
            $search = $request->keyword;
            $schools = $schools->where('school_name', 'like', "%{$search}%");
        }
        $schools = $schools->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.school.index', compact("schools", "count", "list_act"));
    }

    public function createSchool()
    {
        $types = $this->type->select("id", "type_name")->get();
        $majors = Major::select("id", "major_name", "sector_id")->get();
        $areas = $this->getArea();
        $sectors = Sector::select("id", "name")->get();
        return view("admin.school.create", compact("types", "majors", "sectors", "areas"));
    }

    public function storeSchool($request)
    {
        $request->validate(
            [
                'school_code' => 'required|string|unique:schools,school_code|max:10',
                'school_email' => 'required|string|email|max:255|unique:schools,school_email',
                'school_name' => 'required|string|max:200',
                'school_address' => 'string|max:500',
                'school_phone' => 'string|regex:/^0[0-9]{9,10}$/|max:11',
                'school_image' => 'mimes:jpg,png,gif,webp|max:20000'
            ],
            [
                'required' => ':attribute không được bỏ trống!',
                'max' => ':attribute có độ dài lớn nhất :max ký tự!',
                'email' => 'không đúng định dạng!',
                'unique' => ':attribute đã được sử dụng',
                'mimes' => ':attribute phải có định dạng :mimes!',
            ],
            [
                'school_code' => "Mã trường",
                'email' => "Email",
                'school_name' => "Tên trường",
                'school_address' => 'Địa chỉ',
                'school_website' => 'Địa chỉ trang web',
                'school_image' => 'logo'
            ]
        );
        // dd($request->all());

        try {
            $school = $request->except(['_token', 'major', 'sector', 'search_major', 'type']);
            $type = json_encode($request->type);
            $school_major = json_encode($request->major);
            $school['types'] = $type;
            $school['school_majors'] = $school_major;
            $school['school_image'] = $this->saveImage($request, 'school_image');
            $school['school_logo'] = $this->saveImage($request, 'school_logo');
            $this->school->create($school);
            return redirect("admin/school")->with("success", "Thêm trường học thành công!");
        } catch (Exception $ex) {
            return redirect("admin/school")->with("danger", "Thêm trường học thất bại!" . $ex->getMessage());
        }
    }

    public function editSchool($id)
    {
        $types = $this->type->select("id", "type_name")->get();
        $school = $this->school->find($id);
        $major = json_decode($school->school_majors);
        $majors = Major::select("id", "major_name", "sector_id")->get();
        $areas = $this->getArea();
        if ($major != null) {
            $majors->map(function ($item) use ($major) {
                $item->checked = in_array($item->id, $major) ? "checked" : "";
                return $item;
            });
        }
        $sectors = Sector::select("id", "name")->get();
        return compact("types", "school", "majors", "sectors", "areas");
    }

    public function updateSchool($request, $id)
    {
        $rules = [
            'school_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('schools')->ignore($id),
            ],
            'school_name' => 'required|string|max:200',
            'school_address' => 'string|max:500',
            'school_image' => 'mimes:jpg,png,gif,webp|max:20000',
        ];

        $messages = [
            'required' => ':attribute không được bỏ trống!',
            'max' => ':attribute có độ dài lớn nhất :max ký tự!',
            'email' => 'không đúng định dạng!',
            'unique' => ':attribute đã được sử dụng',
            'mimes' => ':attribute phải có định dạng :mimes!',
        ];

        $attributes = [
            'school_code' => 'Mã trường',
            'school_email' => 'Email',
            'school_name' => 'Tên trường',
            'school_address' => 'Địa chỉ',
            'school_phone' => 'Số điện thoại',
            'school_image' => 'Logo',
        ];

        if ($request->school_email != null) {
            $rules['school_email'] = [
                'max:255',
                Rule::unique('schools')->ignore($id),
            ];
        }
        if ($request->school_phone != null) {
            $rules['school_phone'] = [
                'string',
                'regex:/^0[0-9]{9,10}$/',
                'max:11'
            ];
        }
        $request->validate($rules, $messages, $attributes);

        try {
            $school = $request->except(['_token', 'major', 'sector', 'search_major', 'type', 'benchmark']);
            $type = json_encode($request->type);
            $school_major = json_encode($request->major);
            $school['types'] = $type;
            $school['school_majors'] = $school_major;
            $school_old = $this->school->find($id);
            $school['school_image'] = $this->saveImage($request, 'school_image', $school_old->school_image);
            $school['school_logo'] = $this->saveImage($request, 'school_logo', $school_old->school_logo);
            $school_old->update($school);
            return redirect()->back()->with("success", "Cập nhật thông tin trường học thành công!");
        } catch (Exception $ex) {
            return redirect()->back()->with("danger", "Cập nhật thông tin trường học thất bại! " . $ex->getMessage());
        }
    }

    public function removeSchool($id)
    {
        try {
            $school = $this->school->withTrashed()->find($id);
            if ($school != null) {
                $school->delete();
                return redirect()->back()->with('success', "Ẩn trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        } catch (Exception $ex) {
            return redirect()->back()->with('success', "Ẩn trường thất bại!");
        }
    }

    public function restoreSchool($id)
    {
        try {
            $school = $this->school->withTrashed()->find($id);
            if ($school != null) {
                $school->restore();
                return redirect()->back()->with('success', "Hiển thị trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        } catch (Exception $ex) {
            return redirect()->back()->with('success', "Hiển thị trường thất bại!");
        }
    }

    public function deleteSchool($id)
    {
        try {
            $school = $this->school->withTrashed()->find($id);
            if ($school != null) {
                $school->forceDelete();
                return redirect()->back()->with('success', "Xóa trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        } catch (Exception $ex) {
            return redirect()->back()->with('success', "Xóa trường thất bại!");
        }
    }
    public function actionSchool($req){
        $listcheck = $req->input('list_check');
        if($listcheck){
            if(!empty($listcheck)){

                $act = $req->input('act');
                if($act == "remove"){
                    School::destroy($listcheck);
                    return redirect('admin/school')->with('success', "Bạn đã vô hiệu hóa thành công!",);
                }
                if($act == 'restore'){
                    School::withTrashed()
                        ->whereIn('id', $listcheck)
                        ->restore();
                    return redirect('admin/school')->with('success', "Bạn đã khôi phục thành công!");
                }
                if($act == 'delete'){
                    School::withTrashed()
                        ->whereIn('id', $listcheck)
                        ->forceDelete();
                    return redirect('admin/school')->with('success', "Bạn đã xóa vĩnh viễn thành viên!");
                }
            }
        }
    }


    public function count()
    {
        $count = [];
        $count['all_school'] = $this->school->withTrashed()->count();
        $count['school_active'] = $this->school->all()->count();
        $count['school_hide'] = $this->school->onlyTrashed()->count();
        return $count;
    }
    public function countSchool(){
        return $this->school->count();
    }

    public function total()
    {
    }
    public function getBenchmark(){
        return $this->school->with('benchmarks')
               ->select('id', 'school_name')
               ->get();
    }
    public function storeBenchmark($request){

    }
    public function updateBenchmark($request){

    }
    //Api

    public function getOutstendingSchools()
    {
        try {
            $schools = Cache::get('OutstendingSchools');
            if($schools == null){
                $schools = $this->school
                    ->select('school_code', 'school_name', 'school_address', 'school_logo', 'school_description')
                    ->limit(12)
                    ->get();
            }
            return $schools;
        } catch (Exception $e) {
            // Log ra lỗi và trả về null khi thất bại
            Log::error('Failed to retrieve outstanding schools: ' . $e->getMessage());
            return null;
        }
    }
    public function getAllSchool($s)
    {
        if($s != ""){
            $schools = School::where('school_name', 'like', "%{$s}%")->get();
        }else{
            $schools = Cache::get('schools');
            if($schools == null){
                $schools = School::get();
                Cache::put('schools',$schools, 86400 /* ~ 1 ngày */);
            }
        }
        return $schools;
    }
    public function  getSchool($school_code){
        $school = $this->school->where("school_code", $school_code)->first();
        return $school;
    }

    public function getSchoolBySectorId($sector_id){
        $schools = School::whereHas('majors', function ($query) use ($sector_id) {
            $query->where('sector_id', $sector_id);
        })->get();
        return $schools;
    }

    public function getschoolByAreaCodename($code){
        $schools = $this->school->where('area_id', $code)->get();
        return $schools;
    }
}
