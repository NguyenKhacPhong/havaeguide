@extends('layouts.admin');
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
            <h5 class="m-0 ">Danh sách bình luận</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" placeholder="Tìm kiếm">
                    <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('admin.product.list')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_pro']}})</span></a>
                <a href="{{route('admin.product.list.status', 'active')}}" class="text-primary">Hiển thị<span class="text-muted">({{$count['pro_active']}})</span></a>
                <a href="{{route('admin.product.list.status', 'del')}}" class="text-primary">Ẩn<span class="text-muted">({{$count['pro_remove']}})</span></a>
            </div>
            <form action="{{route('admin.product.action')}}">
                @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="" name="act">
                    <option >Chọn</option>
                    @foreach($list_act as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Người bình luận</th>
                        <th scope="col">Nội dung</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($comments->total() > 0)
                    @php
                    $count = 0;
                    @endphp
                    @foreach($comments as $comment)
                    @php
                    $count++;
                    @endphp
                    <tr class="">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$product->id}}">
                        </td>
                        <td>{{$count}}</td>
                        <td><a href="#">{{$comment->name}}</a></td>
                        <td>{{$comment->content}}</td>
                        <td>{{$comment->create_at}}</td>
                        <td>{{$comment->status}}</td>
                        <td>
                            @if($comment->deleted_at == null)
                            <a href="{{route('admin.product.edit', $comment->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>

                            <a href="{{ route('admin.product.remove', $comment->id) }}" onclick="return confirm('Bạn có chắc chắn muốn ẩn sản phẩm này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                            @else
                            <a href="{{ route('admin.product.restore', $comment->id) }}" onclick="return confirm('Bạn có hiển thị lại sản phẩm này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                            <a href="{{ route('admin.product.delete', $comment->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
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
                {{$comments->links()}}
            </div>
        </div>
    </div>
</div>
@endsection