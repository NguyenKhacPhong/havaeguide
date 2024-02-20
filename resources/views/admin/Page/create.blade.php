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
            Thêm trang
        </div>
        <div class="card-body">
            <form action="{{route('admin.page.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="slug">Đường dẫn tĩnh</label>
                    <input class="form-control" type="text" name="slug" id="slug">
                    @error('slug')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Tên trang</label>
                    <input class="form-control" type="text" name="name" id="name">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                        
                <div class="form-group">
                    <label for="content">Nội dung trang</label>
                    <textarea name="content" class="form-control" id="content" cols="30" rows="5"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    tinymce.init(
        {
            selector: 'textarea#content',
            themes: 'silver',
            plugins: "link image code table advtable lists checklist preview fullpage powerpaste fullscreen searchreplace autolink directionality advcode visualblocks visualchars media table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed",
            toolbar: 'undo redo | styleselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | outdent indent |formatselect | numlist bullist outdent indent  | removeformat | fullscreen',
            height: 1000,
        }
    );
</script>
@endsection
@section("css")
<style>
    .list_major{
        height:300px; 
        overflow: scroll;
        font-size:18px
    }
    .dropdown-type{
        padding: 7px;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
    }
    .dropdown_inner {
        font-size: 20px;
        display: none;
        width: 100%;
        flex-wrap: wrap;
    }
    .type_checkbox{
        width: 25%;
    }
</style>
@endsection
