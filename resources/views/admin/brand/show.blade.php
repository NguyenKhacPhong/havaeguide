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
                    Thêm thương hiệu
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.brand.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên thương hiệu</label>
                            <input class="form-control" type="text" name="name" id="name">
                            @error('image')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Mô tả</label>
                            <textarea name="description" id="description" class="form-control" rows="8"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Ảnh minh họa</label>
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
                        <button type="submit" name="update" class="btn btn-primary btn_action">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thương hiệu
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên thương hiệu</th>
                                <th scope="col" style="width:50%">Mô tả</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($brands->total() > 0)
                            @foreach($brands as $brand)
                            <tr>
                                <td>
                                    <input type="checkbox" name="list_check[]" value="{{$brand->id}}">
                                </td>
                                <td class="img-brand"><img src="{{ asset('images/'.$brand->image) }}" alt="Hình ảnh"></td>
                                <td>{{$brand->name}}</td>
                                <td style="width: 55%">
                                    <div class="brand_des">{{$brand->description}}</div>
                                </td>
                                <td>
                                    <button id="update_brand" data_id="{{$brand->id}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                                    <a href="{{route('admin.brand.delete', $brand->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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