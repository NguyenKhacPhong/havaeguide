<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SchoolType\ISchoolTypeRepository;

class SchoolTypeController extends Controller
{
    //
    private ISchoolTypeRepository $typeRepo;
    function __construct(ISchoolTypeRepository $typeRepo)
    {
        $this->typeRepo = $typeRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'school']);
            return $next($request);
        });
    }

    public function Index(Request$request, $status = ''){
        return $this->typeRepo->getSchoolType($request, $status);
    }
    public function Store(Request $request){
        return $this->typeRepo->storeSchoolType($request);
    }

    public function edit(Request $request){
        return $this->typeRepo->editSchoolType($request);
    }

    public function update(Request $request){
        return $this->typeRepo->updateSchoolType($request);
    }

    public function remove($id){
        return $this->typeRepo->removeSchoolType($id);
    }

    public function restore($id){
        return $this->typeRepo->restoreSchoolType($id);
    }

    public function delete($id){
        return $this->typeRepo->deleteSchoolType($id);
    }
}
