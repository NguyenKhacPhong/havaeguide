<?php
namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Major;
use App\Models\Sector;
use App\Models\SessionUser;
use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Exception;
use Cache;

class CommentRepository extends Repositories implements ICommentRepository
{
    private $major;
    private $sector;
    private $comment;
    function __construct(Major $major,Sector $Sector, Comment $comment)
    {
        $this->major = $major;
        $this->sector = $Sector;
        $this->comment = $comment;
    }
    public function find($id){
        return $this->major->find($id);
    }
    public function getComments($request, $status)
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

    public function createComment(){
        $sectors = $this->sector->select("id", "name")->get();
        return view("admin.major.create", compact("sectors"));
    }

    public function storeComment($request){
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

    public function editComment($id){
        $Sectors = $this->sector->select("id", "Sector_name")->get();
        $major = $this->find($id);
        return compact("Sectors", "major");
    }
    public function updateComment($request, $id){
        $rules = [
            'major_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('majors')->ignore($id),
            ],
            'major_name' => 'required|string|max:200',
            'major_address' => 'string|max:500',
            'major_phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9,10}$/',
                'max:11',
            ],
            'major_image' => 'mimes:jpg,png,gif,webp|max:20000',
        ];

        $messages = [
            'required' => ':attribute không được bỏ trống!',
            'max' => ':attribute có độ dài lớn nhất :max ký tự!',
            'email' => 'không đúng định dạng!',
            'unique' => ':attribute đã được sử dụng',
            'mimes' => ':attribute phải có định dạng :mimes!',
        ];

        $attributes = [
            'major_code' => 'Mã trường',
            'major_email' => 'Email',
            'major_name' => 'Tên trường',
            'major_address' => 'Địa chỉ',
            'major_phone' => 'Số điện thoại',
            'major_image' => 'Logo',
        ];

        if ($request->major_email != null) {
            $rules['major_email'] = [
                'max:255',
                Rule::unique('majors')->ignore($id),
            ];
        }

        $request->validate($rules, $messages, $attributes);

        try {
            $major = $request->except('_token');
            if ($request->hasFile('major_image')) {
                $image = $request->file('major_image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $major['major_image'] = $fileName;
            }

            $this->major->find($id)->update($major);

            return redirect("admin/major")->with("success", "Cập nhật thông tin trường học thành công!");
        } catch(Exception $ex) {
            return redirect("admin/major")->with("danger", "Cập nhật thông tin trường học thất bại! " . $ex->getMessage());
        }
    }

    public function removeComment($id){
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
    public function restoreComment($id){
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
    public function deleteComment($id){
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

    public function getCommentsBySchoolCode($code){
        return $this->comment
            ->join('schools', 'schools.id', '=', 'comments.school_id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('schools.school_code', $code)
            ->select('comments.user_id', 'users.avatar', 'users.name as user_name', 'comments.content', 'comments.created_at')
            ->get();
        }
    public function addComment($request){
        $user = SessionUser::where('token', $request->header('token'))
            ->select('user_id')
            ->first();
        $comment = [
            "user_id" => $user->user_id,
            "school_id" => $request->school_id,
            "content" =>$request->content
        ];
        try{
            return $this->comment->create($comment);
        }catch(Exception $e)
        {
            return null;
        }
    }
}
