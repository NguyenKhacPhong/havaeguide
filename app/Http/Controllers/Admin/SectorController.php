<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Sector\ISectorRepository;

class SectorController extends Controller
{
    //
    private $sectorRepo;
    function __construct(ISectorRepository $sectorRepo)
    {
        $this->sectorRepo = $sectorRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'sector']);
            return $next($request);
        });
    }

    public function index(Request $request, $status = '')
    {
        return $this->sectorRepo->getSectors($request, $status);
    }
    public function store(Request $request){
        return $this->sectorRepo->storeSector($request);
    }

    public function edit(Request $request){
        return $this->sectorRepo->editSector($request);
    }

    public function update(Request $request){
        return $this->sectorRepo->updateSector($request);
    }

    public function remove($id){
        return $this->sectorRepo->removeSector($id);
    }

    public function restore($id){
        return $this->sectorRepo->restoreSector($id);
    }

    public function delete($id){
        return $this->sectorRepo->deleteSector($id);
    }
}
