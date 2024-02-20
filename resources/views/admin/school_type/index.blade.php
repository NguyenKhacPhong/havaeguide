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
            <div class="card action_type">
                <div class="card-header font-weight-bold">
                    Thêm hệ đào tạo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.type.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="id" id="id">
                            <label for="type_name">Tên hệ đào tạo</label>
                            <input class="form-control" type="text" name="type_name" id="type_name">
                            @error('type_name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <button type="submit" name="create" class="btn btn-primary btn_create">Thêm mới</button>
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Hệ đào tạo
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên hệ đào tạo</th>
                                <th scope="col">Cập nhật mới nhất</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($SchoolTypes->total() > 0)
                            @foreach($SchoolTypes as $SchoolType)
                            <tr>
                                <td>
                                    <input type="checkbox" name="list_check[]" value="{{$SchoolType->id}}">
                                </td>
                                <td style="width:50%">{{$SchoolType->type_name}}</td>
                                <td style="width:30%">
                                    {{$SchoolType->updated_at}}
                                </td>
                                <td> 
                                    @if($SchoolType->deleted_at == null)
                                        <button id="update_type" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit" data_id="{{$SchoolType->id}}"><i class="fa fa-edit"></i></button>
                                    <form action="{{route('admin.type.remove',$SchoolType->id)}}" >
                                        <button onclick="return confirm('Bạn có chắc chắn muốn ẩn hệ này không?')" class="btn btn-success btn-sm rounded-0 text-white btn_remove" type="submit" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></button>
                                    </form>
                                    @else
                                    <form action="{{route('admin.type.restore', $SchoolType->id)}}" enctype="multipart/form-data">
                                        <button onclick="return confirm('Bạn có muốn khởi động lại tài khoản này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="submit" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></button>
                                    </form>
                                    <form action="{{route('admin.type.delete', $SchoolType->id)}}" enctype="multipart/form-data">
                                        <button class="btn btn-danger btn-sm rounded-0 text-white" type="submit" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></button>
                                    </form>
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
@section('js')
<script type="text/javascript">
    $(function() {
    $(".card-body #update_type").click(function() {
        var id = $(this).attr('data_id');

        var strBtn = "<input type='submit' class='btn btn-primary btn_update' value='Cập nhật'>";
        if ($(".action_type .btn_update").length === 0) {
            $(".action_type form").append(strBtn);
            $(".action_type .btn_create").attr('data_id', id);
        }
        $.ajax({
            type: "GET",
            url: "{{ route('admin.type.edit') }}",
            data: { id: id },
            dataType: "json",
            success: function(data) {
                $("#type_name").val(data.type_name);
                var url = "{{ route('admin.type.update')}}";
                $(".action_type form").attr("action", url);
                $("#id").val(data.id);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + errorThrown);
            }
        });
    });
});
</script>
@endsection

@section('css')
<style>
    form {
        display: inline;
    }
</style>
@endsection