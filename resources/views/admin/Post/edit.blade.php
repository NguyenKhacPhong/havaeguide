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
            Thêm bài viết
        </div>
        <div class="card-body">
            <form action="{{route('admin.post.update', $post->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="slug">Đường dẫn tĩnh</label>
                    <input class="form-control" type="text" name="slug" id="slug" value="{{$post->slug}}">
                    @error('slug')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="title">Tiêu đề bìa viết</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{$post->title}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <label for="image">Hình ảnh</label>
                    <div>
                        <input type="file" name="image" id="post_logo_file"
                            accept="image/gif, image/jpeg, image/png" onchange="loadFileImage(event, 'post_image')">
                        <div class="image">
                            <img id="post_image" class="image-show"
                                src="{{ asset('images/' . ($post->image ?? 'image_blank.jpg')) }}"
                                alt="Ảnh minh họa">
                        </div>
                    </div>
                <div class="form-group">
                    <label for="content">Nội dung bài viết</label>
                    <textarea name="content" class="form-control" id="content" cols="30" rows="5">{{$post->content}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
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
            plugins: "link image code table advtable lists checklist preview fullpost powerpaste fullscreen searchreplace autolink directionality advcode visualblocks visualchars media table charmap hr postbreak nonbreaking anchor toc insertdatetime advlist textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed",
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
