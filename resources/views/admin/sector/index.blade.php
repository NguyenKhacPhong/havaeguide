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
            <div class="card action_sector">
                <div class="card-header font-weight-bold">
                    Thêm nhóm ngành
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.sector.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="id" id="id">
                            <label for="name">Tên nhóm ngành</label>
                            <input class="form-control" type="text" name="name" id="name" autofocus>
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" type="text" name="description" id="description"></textarea>
                            @error('description')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Ảnh minh họa</label>
                            <div>
                                <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png, image/webp" onchange="loadFile(event)">
                                <div class="avatar-img">
                                    <img id="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                                </div>
                            </div>
                            @error('image')
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
                    Nhóm ngành học
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th style="width:60%" scope="col">Tên nhóm ngành</th>
                                <th style="width:25%" scope="col">Ngày cập nhật</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($Sectors->total() > 0)
                            @foreach($Sectors as $Sector)
                            <tr>
                                <td>
                                    <input type="checkbox" name="list_check[]" value="{{$Sector->id}}">
                                </td>
                                <td style="width:60%">{{$Sector->name}}</td>
                                <td style="width:25%">
                                    {{$Sector->updated_at}}
                                </td>
                                <td> 
                                    @if($Sector->deleted_at == null)
                                        <button id="update_sector" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit" data_id="{{$Sector->id}}"><i class="fa fa-edit"></i></button>
                                    <form action="{{route('admin.sector.remove',$Sector->id)}}" >
                                        <button onclick="return confirm('Bạn có chắc chắn muốn ẩn nhóm ngành này không?')" class="btn btn-success btn-sm rounded-0 text-white btn_remove" type="submit" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></button>
                                    </form>
                                    @else
                                    <form action="{{route('admin.sector.restore', $Sector->id)}}" enctype="multipart/form-data">
                                        <button onclick="return confirm('Bạn có muốn hiển thị nhóm ngành này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="submit" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></button>
                                    </form>
                                    <form action="{{route('admin.sector.delete', $Sector->id)}}" enctype="multipart/form-data">
                                        <button class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('Bạn có muốn xóa nhóm ngành này không?')" type="submit" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></button>
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
                <div class="ml-3">
                    {{ $Sectors->links() }}
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(function() {
        $(".card-body #update_sector").click(function() {
            var id = $(this).attr('data_id');

            var strBtn = "<input type='submit' class='btn btn-primary btn_update' value='Cập nhật'>";
            if ($(".action_sector .btn_update").length === 0) {
                $(".action_sector form").append(strBtn);
                $(".action_sector .btn_create").attr('data_id', id);
            }
            $.ajax({
                type: "GET",
                url: "{{ route('admin.sector.edit') }}",
                data: { id: id },
                dataType: "json",
                success: function(data) {
                    $("#name").val(data.name);
                    $("input#id").val(data.id);
                    tinymce.get("description").setContent(data.description == null ? "" : data.description);
                    var url = "{{ route('admin.sector.update')}}";
                    $(".action_sector form").attr("action", url);
                    src = "http://localhost/havaeguide/public/images/"+data.image;
                    $("form #image-show").attr('src', src);
                },
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Lỗi: " + errorThrown);
                }
            });
            
        });

        tinymce.init(
            {
                selector: 'textarea#description',
                plugins: "link image code table advtable lists checklist",
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
                height: 500,
            }
        );

        
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