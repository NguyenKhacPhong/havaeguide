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
            <div class="analytic">
                <a href="{{route('admin.major.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_major']}})</span></a>
                <a href="{{route('admin.major.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['major_active']}})</span></a>
                <a href="{{route('admin.major.status', 'hide')}}" class="text-primary">Ẩn<span class="text-muted">({{$count['major_hide']}})</span></a>
            </div>
            <div class="row align-items-center">
                <div class="form-action py-3 col-6">
                    <form action="{{route('admin.major.action')}}">
                        @csrf
                        <div class="col-6 text-left d-flex align-items-center">
                            <select class="form-control mr-1" id="" name="act" style="width:150px">
                                <option >Chọn</option>
                                @foreach($list_act as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                        </div>
                    </form>
                </div>

                <div id="import_export_file" class="col-6 d-flex justify-content-end">
                    <div id ="import_export_file_inner">
                        <form action="{{route('admin.major.import')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file_import" id="file_import">
                            <input type="submit" value="Thêm đồng loạt" class="mt-1 btn btn-success">
                        </form>
                        <form action="{{route('admin.major.export')}}" method="POST">
                            @csrf
                            <input type="submit" value="Xuất file" name="Excel_export" class="btn btn-success mt-1 mb-1 ml-2">
                        </form>
                    </div>
                    <div class="error">
                        @error('file_import')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">Tên ngành</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($majors->total() > 0)
                    @foreach($majors as $major)
                    <tr class="">
                        <td style="width:3%; overflow:hidden;">
                            <input type="checkbox" name="list_check[]" value="{{$major->id}}">
                        </td>
                        <td style="width:30%; overflow:hidden;"><a href="#">{{$major->major_name}}</a></td>
                        <td class="major_description" style="width:50%; overflow:hidden;"></td>
                        <td style="width:10%; overflow:hidden;">{{$major->created_at}}</td>
                        <td style="width:10%; overflow:hidden;">
                            @if($major->deleted_at == null)
                            <a href="{{route('admin.major.edit', $major->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>

                            <a href="{{ route('admin.major.remove', $major->id) }}" onclick="return confirm('Bạn có chắc chắn muốn ẩn ngành này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                            @else
                            <a href="{{ route('admin.major.restore', $major->id) }}" onclick="return confirm('Bạn có hiển thị lại ngành này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                            <a href="{{ route('admin.major.delete', $major->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn ngành này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
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
            <div>
                {{$majors->links()}}
            </div>
            
    </div>
</div>
@endsection
@section("css")
<style>
    #import_export_file form{
        display: inline;
    }
    td a{
        display: inline;
    }
    .major_description{
        display:-webkit-box;
        -webkit-line-clamp:3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }
</style>
@endsection
