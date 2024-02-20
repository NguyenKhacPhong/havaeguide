@extends('layouts/admin')
@section('content')
<div id="content" class="container-fluid">
<div class="card">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">{{session('success')}}</div>
    @endif
    @if(session('danger'))
    <div class="alert alert-danger alert-dismissible">{{session('danger')}}</div>
    @endif
    <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
        <h5 class="m-0 ">Danh sách phân quyền</h5>
        <div class="form-search form-inline">
            <form action="">
                <input type="" class="form-control form-search" name="keyword" placeholder="Tìm kiếm" value="{{request()->input('keyword')}}">
                <input type="submit" name="" value="Tìm kiếm" class="btn btn-primary">
            </form>
        </div>
    </div>
    <div class="card-body">
        <form action="" method="">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="checkall">
                        </th>
                        <th scope="col" class="col-1">ID</th>
                        <th scope="col" class="col-8">Name</th>
                        <th scope="col" class="col-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $model)
                    <tr>
                        <td>
                            <input type="checkbox" name="list_check[]" value="">
                        </td>
                        <td>{{$model->id}}</td>
                        <td>{{$model->name}}</td>
                        <td>
                            <a href="" class="btn btn-xs btn-primary">Sửa</a>
                            <a href="" class="btn btn-xs btn-danger">Xóa</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        {{ $data->links() }}

    </div>
</div>
</div>
</div>


@endsection
