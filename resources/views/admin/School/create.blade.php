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
            Thêm sản phẩm
        </div>
        <div class="card-body">
            <form action="{{route('admin.school.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="school_code">Mã trường</label>
                            <input class="form-control" type="text" name="school_code" id="product-code">
                            @error('school_code')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="school_name">Tên trường</label>
                            <input class="form-control" type="text" name="school_name" id="name">
                            @error('school_name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="school_address">Địa chỉ</label>
                                <input class="form-control" type="text" name="school_address" id="school_address">
                                @error('school_address')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="school_phone">Phone</label>
                                <input class="form-control" type="text" name="school_phone" id="school_phone">
                                @error('school_phone')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="school_email">Email</label>
                            <input class="form-control" type="text" name="school_email" id="school_email">
                            @error('school_email')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="school_website">Website</label>
                            <input class="form-control" type="text" name="school_website" id="school_website">
                            @error('school_website')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="school_description">Mô tả ngắn về trường</label>
                            <textarea id="school_description" name="school_description" class="form-control" cols="30" rows="11"></textarea>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="school_detail">Thông tin chi tiết trường</label>
                    <textarea name="school_detail" class="form-control" id="school_detail" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Logo trường</label>
                    <div>
                        <input type="file" name="school_logo" id="school_logo_file" accept="image/gif, image/jpeg, image/png" onchange="loadFileImage(event, 'school_logo')">
                        <div class="image">
                            <img id="school_logo" class="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                        </div>
                    </div>
                    @error('school_logo')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Banner trường</label>
                    <div>
                        <input type="file" name="school_image" id="school_image_file" accept="image/gif, image/jpeg, image/png" onchange="loadFileImage(event, 'school_image')">
                        <div class="image">
                            <img id="school_image" class="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                        </div>
                    </div>
                    @error('school_image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type_id">Hệ đào tạo</label>
                    <div class="dropdown-type">
                        Chọn hệ đào tạo
                    </div>
                    <div class="dropdown_inner">
                        @foreach($types as $item)
                            <div class="type_checkbox">
                                <input type="checkbox" value="{{$item->id}}" id="type-{{$item->id}}" name="type[]"/>
                                <label for="type-{{$item->id}}">{{$item->type_name}}</label><br/>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="area_id">Khu vực</label>
                    <select class="form-control" id="area_id" name="area_id">
                        <option value="">Chọn khu vực</option>
                        @foreach($areas as $item)
                        <option value="{{$item->code}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="sector">Các ngành đào tạo</label>
                    <div class="filter-major row">
                        <input type="text" name="search_major" id="search_major" class="form-control col-6" placeholder="Nhập tên ngành">
                        <select class="form-control col-6" id="sector" name="sector">
                            <option value="">Chọn nhóm ngành</option>
                            @foreach($sectors as $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="list_major row mt-2" >
                        <div class="left col-6">

                        </div>
                        <div class="right col-6">

                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    $(document).ready(function(){
        var majors = {!! json_encode($majors) !!};
        var list_major_left = "";
        var list_major_right = "";
        var i = 0;
        majors.forEach(element => {
            var major_name = element['major_name'];
            var major_id = element['id'];
            var sector_id = element['sector_id'];
            var checked = element['checked'];
            var major_checkbox = '<div class="form-check" data-sector_id="' +sector_id+ '" ><input class="form-check-input" type="checkbox" name="major[]" id="' + major_id + '" value="' + major_id + '" '+checked+'><label class="form-check-label" for="' + major_id + '">' + major_name + '</label>\
                </div>';
                    if(i % 2 == 0) {
                        list_major_left += major_checkbox;
                    } else {
                        list_major_right += major_checkbox;
                    }
                i++;
            });
            $('.list_major .left').html(list_major_left);
            $('.list_major .right').html(list_major_right);
        //lọc theo nhóm ngành
        $("#sector").change(function() {
            var selectedSector = $(this).val();
            if (selectedSector !== "") {
            // Show the form-check elements that match the selected sector
            $('.list_major .form-check').each(function() {
                var sectorId = $(this).data("sector_id");
                if (sectorId == selectedSector) {
                $(this).show();
                } else {
                $(this).hide();
                }
            });
            } else {
            // Show all form-check elements if no sector is selected
            $('.list_major .form-check').show();
            }
        });
        //search nhóm ngành
        $("#search_major").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".form-check").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

        $(".dropdown-type").on("click", function(){
            $(".dropdown_inner").slideToggle(function() {
                if ($(this).is(":visible")) {
                    $(this).css("display", "flex");
                }
            });
        });

    });
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
        border: 1px solid #cccccc;
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
