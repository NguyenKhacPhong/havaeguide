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
            <h5 class="m-0 ">Danh sách thành viên</h5>
            <div class="form-search form-inline">
                <form action="">
                    <input type="" class="form-control form-search" name="keyword" placeholder="Tìm kiếm" value="{{request()->input('keyword')}}">
                    <input type="submit" name="" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('admin.user.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_user']}})</span></a>
                <a href="{{route('admin.user.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['user_active']}})</span></a>
                <a href="{{route('admin.user.status', 'del')}}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count['user_remove']}})</span></a>
            </div>
            <form action="{{url('admin/user/action')}}" method="">

                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="act">
                        <option>Chọn</option>
                        @foreach($list_act as $key=>$act)
                        <option value="{{$key}}">{{$act}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Họ tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Quyền</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->total() > 0)
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value="{{$user->id}}">
                            </td>
                            <th scope="row">{{$loop->index+1}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>Admintrator</td>
                            <td>{{$user->created_at}}</td>
                            <td>
                                @if($user->deleted_at == null)
                                <a href="{{route('admin.user.edit', $user->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @if($user->id != Auth::id())
                                <a href="{{ route('admin.user.remove', $user->id) }}" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa tài khoản này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                                @endif
                                @else
                                @if($user->id != Auth::id())
                                <a href="{{ route('admin.user.restore', $user->id) }}" onclick="return confirm('Bạn có muốn khởi động lại tài khoản này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                                @endif
                                <a href="{{ route('admin.user.delete', $user->id) }}" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
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
            </form>
            {{ $users->links() }}

        </div>
    </div>
</div>
@endsection
