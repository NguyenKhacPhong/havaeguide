<?php
namespace App\Repositories\SchoolType;

interface ISchoolTypeRepository{
    public function getSchoolType($request, $status);
    public function storeSchoolType($request);
    public function editSchoolType($request);
    public function updateSchoolType($request);
    public function removeSchoolType($id);
    public function restoreSchoolType($id);
    public function deleteSchoolType($id);
    public function count();
    public function total();
    public function getAllType();
}