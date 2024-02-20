<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Major\IMajorRepository;
use App\Imports\ExcelImportMajors;
use App\Exports\ExcelExportMajors;
use Excel;

class MajorController extends Controller
{
    //
    private IMajorRepository $majorRepo;
    public function __construct(IMajorRepository $iMajorRepo){
        $this->majorRepo = $iMajorRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'major']);
            return $next($request);
        });
    }

    public function index(Request $request, $status = ''){
        return $this->majorRepo->getMajor($request, $status);
    }

    public function create()
    {
        return $this->majorRepo->createMajor();
    }

    public function Store(Request $request)
    {
        return $this->majorRepo->storeMajor($request);
    }

    public function importExcel(Request $request)
    {
        $request->validate(
            [
                "file_import" => "required|mimes:xlsx,xls"
            ],
            [
                "required" => ":attribute chưa được thêm vào!",
                "mimes" => ":attribute phải thuộc .xlsx, .xls!"
            ],
            [
                "file_import" => "File import"
            ]
        );
        $path = $request->file('file_import')->getRealPath();
        Excel::import(new ExcelImportMajors, $path);
        return redirect("admin/major")->with('success', 'Thêm thành công!');
    }

    public function edit($id)
    {
        $compact = $this->majorRepo->editMajor($id);
        return view("admin.major.edit", $compact);
    }
    public function update(Request $request, $id)
    {
        return $this->majorRepo->updateMajor($request, $id);
    }   

    public function remove($id){
        return $this->majorRepo->removeMajor($id);
    }

    public function restore($id){
        return $this->majorRepo->restoreMajor($id);
    }

    public function excelExport(){
        return Excel::download(new ExcelExportMajors , 'major.xlsx');
    }

    public function delete($id){
        return $this->majorRepo->deleteMajor($id);
    }
}
