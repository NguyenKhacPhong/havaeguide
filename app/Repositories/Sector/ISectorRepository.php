<?php
namespace App\Repositories\Sector;

interface ISectorRepository{
    public function getSectors($request, $status);
    public function storeSector($request);
    public function editSector($request);
    public function updateSector($request);
    public function removeSector($id);
    public function restoreSector($id);
    public function deleteSector($id);
    public function count();
    public function total();
    //Api
    public function getAllSector();
    public function getSector($sector_id);
}
