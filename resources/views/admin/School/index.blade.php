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
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách trường học</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="text" name="keyword" class="form-control form-search" placeholder="Tìm kiếm">
                    <input type="submit" class="btn btn-primary" value="Tìm kiếm">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic d-flex">
                <div class="col-6">
                    <a href="{{route('admin.school.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_school']}})</span></a>
                    <a href="{{route('admin.school.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['school_active']}})</span></a>
                    <a href="{{route('admin.school.status', 'hide')}}" class="text-primary">Ẩn<span class="text-muted">({{$count['school_hide']}})</span></a>
                </div>
                <div id="import_export_file" class="col-6 text-right">
                    <form action="{{route('admin.school.import')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file_import" id="file_import">
                        <input type="submit" value="Thêm đồng loạt" class="mt-1 btn btn-success">
                    </form>
                    <form action="{{route('admin.school.export')}}" method="POST">
                        @csrf
                        <input type="submit" value="Xuất file" name="Excel_export" class="btn btn-success mt-1 mb-1">
                    </form>
                </div>
            </div>

            <form action="{{route('admin.school.action')}}" method="post">
                @csrf
            <div class="form-action py-3 row">
                <div class="col-6 text-left d-flex align-items-center">
                    <select class="form-control mr-1" id="" name="act" style="width:150px">
                        <option >Chọn</option>
                        @foreach($list_act as $key=>$item)
                            <option value="{{$key}}">{{$item}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">Mã trường</th>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Tên trường</th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Điện thoại</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($schools->total() > 0)
                    @foreach($schools as $school)
                    <tr class="">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$school->id}}">
                        </td>
                        <td style="width:5%; overflow:hidden;"><a href="#">{{$school->school_code}}</a></td>
                        <td ><img style="width:80px; height:80px" src="{{asset('images/'.($school->school_logo ?? "image_blank.jpg"))}}" alt=""></td>
                        <td style="width:35%; overflow:hidden;"><a href="#">{{$school->school_name}}</a></td>
                        <td style="width:30%; overflow:hidden;">{{$school->school_address}}</td>
                        <td style="width:20%;">{{$school->school_phone}}</td>
                        <td style="width:10%;">
                            @if($school->deleted_at == null)
                            <a href="{{route('admin.school.edit', $school->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>

                            <a href="{{ route('admin.school.remove', $school->id) }}" onclick="return confirm('Bạn có chắc chắn muốn ẩn trường này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                            @else
                            <a href="{{ route('admin.school.restore', $school->id) }}" onclick="return confirm('Bạn có hiển thị lại trường này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                            <a href="{{ route('admin.school.delete', $school->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn trường này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9">Không tìm thấy bản ghi nào</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </form>
            <div>
                {{$schools->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section("css")
<style>
    #import_export_file form{
        display: inline;
    }
</style>
@endsection
