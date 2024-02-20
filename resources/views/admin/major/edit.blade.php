@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
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
        <div class="card-header font-weight-bold">
            Thêm ngành đào tạo
        </div>
        <div class="card-body">
            <form action="{{route('admin.major.update', $major->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="major_name">Tên ngành đào tạo</label>
                    <input class="form-control" type="text" name="major_name" id="name" value="{{$major->major_name}}">
                    @error('major_name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="major_description">Thông tin chi ngành</label>
                    <textarea name="major_description" class="form-control" id="major_description" cols="30" rows="5">{{$major->major_description}}</textarea>
                </div>
                <div class="form-group">
                    <label for="type_id">Nhóm ngành</label>
                    <select class="form-control" id="" name="sector_id">
                        <option value="">-- Chọn nhóm ngành --</option>
                        @foreach($Sectors as $sector)
                        <option value="{{$sector->id}}" {{$sector->id == $major->sector_id ? "selected" : ""}}>{{$sector->name}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section("js")
<script>
    $(function(){
        tinymce.init(
        {
            selector: 'textarea#major_description',
            plugins: "link image code table advtable lists checklist",
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
            height: 500,
        }
);
});
</script>
@endsection
