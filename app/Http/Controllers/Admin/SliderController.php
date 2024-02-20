<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Slider\SliderRepository;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    private $sliderRepo;
    public function __construct(SliderRepository $sliderRepo)
    {
        $this->sliderRepo = $sliderRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }

    public function index(){
        $sliders = $this->sliderRepo->getAllSlider();
//        dd($sliders);
        return view('admin.slider.index', compact('sliders'));
    }

    public function store(Request $request){
        $result = $this->sliderRepo->create($request);
        if(empty($result)){
            return redirect('admin/slider')->with('danger', 'Thêm silder không thành công!');
        }else{
            return redirect('admin/slider')->with('success', 'Đã thêm slider thành công!');
        }
    }
    function remove($id){
        $slider = $this->sliderRepo->find($id);
        if($slider){
            $slider->delete();
            return redirect('admin/slider')->with('success', 'Đã ẩn slider thành công!');
        }else{
            return redirect('admin/slider')->with('danger', 'Ẩn slider không thành công!');
        }
    }
    function restore($id){
        $slider = $this->sliderRepo->find($id);
        if($slider){
            $slider->restore();
            return redirect('admin/slider')->with('success', 'Hiển thị lại slider thành công!');
        }else{
            return redirect('admin/slider')->with('danger', 'Hiển thị lại slider không thành công!');
        }
    }
    function delete($id){
        $slider = $this->sliderRepo->find($id);
        if($slider){
            $slider->forceDelete();
            return redirect('admin/slider')->with('success', 'Đã xóa slider thành công!');
        }else{
            return redirect('admin/slider')->with('danger', 'Xóa slider không thành công!');
        }
    }



}
