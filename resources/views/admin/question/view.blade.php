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
            Sửa nội dung trang
        </div>
        <div class="card-body">
                <div class="form-group">
                    <label for="slug">Đường dẫn tĩnh</label>
                    <input class="form-control" value="{{$question->title}}" type="text" name="slug" id="slug" disabled>
                    @error('slug')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Nội dung trang</label>
                    <textarea name="content" class="form-control" id="content" cols="30" rows="5" >
                        {{$question->content}}
                    </textarea>
                </div>
                <form method="post" action="{{route('admin.question.changeStatus',$question->id)}}" >
                    @csrf
                    <select name="status" class="mb-2">
                        <option value="0" {{$question->status ? "" : "selected"}}>Hủy phê duyệt</option>
                        <option value="1" {{$question->status ? "selected" : ""}}>Phê duyệt</option>
                    </select><br/>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
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
            readonly: true,
            menubar: false,
            toolbar: false,
            height: 380,
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
