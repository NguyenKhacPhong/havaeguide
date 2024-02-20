<?php

namespace App\Repositories\School;

interface ISchoolRepository{
    public function getSchools($request, $status);
    public function createSchool();
    public function storeSchool($request);
    public function editSchool($id);
    public function updateSchool($request, $id);
    public function removeSchool($request);
    public function deleteSchool($request);
    public function actionSchool($req);
    public function count();
    public function countSchool();
    public function total();
    public function storeBenchmark($request);
    public function updateBenchmark($request);

    //Api
    public function getOutstendingSchools();
    public function getAllSchool($s);
    public function getSchool($school_code);
    public function getSchoolBySectorId($sector_id);
    public function getschoolByAreaCodename($codename);
}
