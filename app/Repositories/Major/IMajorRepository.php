<?php

namespace App\Repositories\Major;

interface IMajorRepository{
    public function getMajor($request, $status);
    public function createMajor();
    public function storeMajor($request);
    public function editMajor($id);
    public function updateMajor($request, $id);
    public function removeMajor($request);
    public function deleteMajor($request);
    public function count();
    public function total();

    //Api
    public function getAllMajor();
    public function getMajorInArray($arr);
}
