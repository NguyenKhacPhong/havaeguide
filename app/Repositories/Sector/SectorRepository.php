<?php

namespace App\Repositories\Sector;

use App\Models\Sector;
use App\Repositories\Repositories;
use Exception;
use Cache;

class SectorRepository extends Repositories implements ISectorRepository
{
    private $Sector;
    function __construct(Sector $Sector)
    {
        $this->Sector = $Sector;
    }

    public function getSectors($request, $status)
    {
        $Sectors = $this->getModelWithStatus($status, $this->Sector);
        $search = '';
        if ($request->keyword) {
            $search  = $request->keyword;
            $Sectors = $Sectors->where('sector_name', 'like', "%{$search}%");
        }
        $Sectors  = $Sectors->paginate(17);
        $count    = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.sector.index', compact("Sectors", "count", "list_act"));
    }
    public function storeSector($request)
    {
        $request->validate(
            [
                "name" => "required|string|max:200"
            ],
            [
                "required"  => ":attribute không được để trống!",
                "string"    => ":attribute phải là một chuỗi!",
                "max"       => ":attribute không vượi quá 200 ký tự!"
            ],
            [
                "name" => "Tên nhóm ngành"
            ]
        );
        try {
            if (!empty($request->file('image'))) {
                $fileName = 'sector_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path("images"), $fileName);
                $image = $fileName;
            } else $image = 'image_blank.jpg';
            $this->Sector->create([
                "name" => $request->name,
                "description" => $request->description,
                "image" => $image,
            ]);
            return redirect()->back()->with('success', "Đã thêm nhóm ngành thành công!");
        } catch (Exception $ex) {
            return redirect()->back()->with('danger', "Thêm nhóm ngành thất bại! " . $ex->getMessage());
        }
    }

    public function updateSector($request)
    {
        $request->validate(
            [
                "name" => "required|string|max:200"
            ],
            [
                "required"  => ":attribute không được để trống!",
                "string"    => ":attribute phải là một chuỗi!",
                "max"       => ":attribute không vượi quá 200 ký tự!"
            ],
            [
                "name" => "Tên nhóm ngành"
            ]
        );
        try {
            $currentSector = $this->Sector->find($request->id);
            $data = [
                "name" => $request->name,
                "description" => $request->description
            ];
            $data['image'] = $this->saveImage($request, 'school_image',$currentSector->image);
            $currentSector->update($data);
            return redirect()->back()->with('success', "Đã cập nhật nhóm ngành thành công!");
        } catch (Exception) {
            return redirect()->back()->with('danger', "Cập nhật nhóm ngành thất bại!");
        }
    }
    public function editSector($id)
    {;
        $sector = $this->Sector->find($id)->first();
        return response()->json($sector);
    }

    public function removeSector($id)
    {
        try {
            $this->Sector->find($id)->delete();
            return redirect()->back()->with('success', "Ẩn nhóm ngành thành công!");
        } catch (Exception) {
            return redirect()->back()->with('success', "Ẩn nhóm ngành thất bại!");
        }
    }

    public function restoreSector($id)
    {
        try {
            $type = $this->Sector->withTrashed()->find($id);
            if ($type != null) {
                $type->restore();
                return redirect()->back()->with('success', "Hiện thị nhóm ngành thành công!");
            }
            return redirect()->back()->with('danger', "Không có nhóm ngành nào như thế!");
        } catch (Exception) {
            return redirect()->back()->with('success', "Hiển thị nhóm ngành thất bại!");
        }
    }

    public function deleteSector($id)
    {
        try {
            $sector = $this->Sector->withTrashed()->find($id);
            if ($sector != null) {
                $sector->forceDelete();
                return redirect()->back()->with('success', "Xóa nhóm ngành thành công!");
            } else {
                return redirect()->back()->with('danger', "Không tồn tại nhón ngành đó!");
            }
        } catch (Exception) {
            return redirect()->back()->with('danger', "Xóa nhóm ngành thất bại!");
        }
    }
    public function count()
    {
        $count                  = [];
        $count['all_sector']    = $this->Sector->withTrashed()->count();
        $count['sector_active'] = $this->Sector->all()->count();
        $count['sector_hide']   = $this->Sector->onlyTrashed()->count();
        return $count;
    }

    public function total()
    {
    }

    //Api

    public function getAllSector()
    {
        $sectors = Cache::get('sectors');

        if ($sectors == null) {
            $sectors = Sector::select('id', 'name', 'image')->get();
            Cache::put('sectors', $sectors, 86400);
        }
        return $sectors;
    }

    public function getSector($sector_id){
        return $this->Sector->select('id', 'name', 'image', 'description')->find($sector_id);
    }
}
