@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session('success')}}
            </div>
        @endif
        @if(session('danger'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session('danger')}}
            </div>
        @endif
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm slider
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.slider.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <div>
                                    <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png" onchange="loadFile(event)">
                                    <div class="avatar-img">
                                        <img id="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                                    </div>
                                </div>
                                @error('image')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <button type="submit" name="create" class="btn btn-primary btn_action">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách slider
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($sliders) > 0)
                                @foreach($sliders as $slider)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{$slider->id}}">
                                        </td>
                                        <td class="img-slider"><img src="{{ asset('images/'.$slider->url) }}" alt="Hình ảnh"></td>
                                        <td>
                                            @if($slider->deleted_at)
                                                <a href="{{route('admin.slider.delete', $slider->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <a href="{{route('admin.slider.restore', $slider->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{route('admin.slider.remove', $slider->id)}}" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa tạm thời">
                                                    <i class="fa-sharp fa-solid fa-eye-slash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">Không tìm thấy bản ghi nào</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
<style>
    .img-slider img{
        width: 400px;
    }
</style>
