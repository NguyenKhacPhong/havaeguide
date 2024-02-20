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
            <h5 class="m-0 ">Danh sách bài viết</h5>
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
                    <a href="{{route('admin.post.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_post']}})</span></a>
                    <a href="{{route('admin.post.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['post_active']}})</span></a>
                    <a href="{{route('admin.post.status', 'hide')}}" class="text-primary">Ẩn<span class="text-muted">({{$count['post_hide']}})</span></a>
                </div>
            </div>

            <form action="{{route('admin.post.action')}}" method="post">
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
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Tiêu đề</th>
                        <th scope="col">Người tạo</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($posts->total() > 0)
                    @foreach($posts as $post)
                    <tr class="">
                        <td style="width:5%; overflow:hidden;">
                            <input type="checkbox" name="list_check[]" value="{{$post->id}}">
                        </td>
                        <td ><img style="width:80px; height:80px" src="{{asset('images/'.($post->image ?? "image_blank.jpg"))}}" alt=""></td>
                        <td style="width:55%; overflow:hidden;"><a href="{{route('admin.post.edit', $post->id)}}">{{$post->title}}</a></td>
                        <td style="width:15%; overflow:hidden;"><a href="{{route('admin.user.edit', $post->id)}}">{{$post->user_name}}</a></td>
                        <td style="width:15%; overflow:hidden;">{{$post->created_at}}</td>
                        <td style="width:10%;">
                            @if($post->deleted_at == null)
                            <a href="{{route('admin.post.edit', $post->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('admin.post.remove', $post->id) }}" onclick="return confirm('Bạn có chắc chắn muốn ẩn trường này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                            @else
                            <a href="{{ route('admin.post.restore', $post->id) }}" onclick="return confirm('Bạn có hiển thị lại trường này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                            <a href="{{ route('admin.post.delete', $post->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn trường này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
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
                {{$posts->links()}}
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
