@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm người dùng
        </div>
        <div class="card-body">
            <form action="{{url('admin/user/store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name">
                    @error('name')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email">
                    @error('email')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gender">Giới tính</label>
                    <div>
                        <input  type="radio" name="gender" value="nam" checked><label>&nbsp; Nam</label>
                        <input class="ml-3" type="radio" name="gender" value="nữ"><label>&nbsp; Nữ</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Điện thoại</label>
                    <input class="form-control" type="text" name="phone" id="phone">
                </div>
                <div class="form-group">
                    <label for="avatar">Ảnh đại diện</label>
                    <div>
                        <input type="file" name="avatar" id="avatar" onchange="loadFile(event)">
                        <div class="avatar-img">
                            <img id="image-show"  src="{{asset('images/user-blank.png')}}" alt="Ảnh đại diện">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('password')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Xác nhận mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                </div>
                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control" id="">
                        <option>Chọn quyền</option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" name="btnCreate" value="Thêm mới" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection
