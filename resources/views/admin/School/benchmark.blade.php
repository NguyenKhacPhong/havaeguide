@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('danger'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('danger') }}
                </div>
            @endif
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ route('admin.school.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="area_id">Trường học</label>
                        <input type="text" name="search_school" id="search_school" class="form-control "
                            placeholder="Nhập tên trường">
                        <select class="form-control" id="school_name" name="school_name">
                            <option>
                                -- Chọn một trường --
                            </option>
                            @foreach ($schools as $item)
                                <option value="{{ $item->id }}">{{ $item->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group year">
                        <div id="list_year" class="list_year">
                            {{-- Danh sách các năm --}}
                        </div>
                    </div>
                    <div class="form-group year">
                        <label for="area_id">Năm</label>
                        <input type="number" name="year" id="year-benchmark" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="school_detail">Thông tin điểm chuẩn</label>
                        <textarea name="school_detail" class="form-control" id="school_detail" cols="30" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            var benchmarks;
            //search nhóm ngành
            $("#search_school").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#school_name option").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $('#school_name').on('change', function() {
                var school_id = $(this).val();
                var selected_school = {!! json_encode($schools->toArray()) !!}
                    .find(function(school) {
                        return school.id == school_id;
                    });
                if (selected_school) {
                    benchmarks = selected_school.benchmarks;
                    $('#list_year').html('');
                    for (var i = 0; i < benchmarks.length; i++) {
                        $('#list_year').append('<div class="btn-year btn btn-success" data-id="' + i +
                            '">' + benchmarks[i].year + '</div>');
                    }
                }
                $('.btn-year').on('click', function() {
                    var id = $(this).data('id');
                    var currentYear = new Date().getFullYear();
                    $('#year-benchmark').attr({
                        max: currentYear,
                        value: benchmarks[id].year
                    });
                    tinymce.get("school_detail").setContent(benchmarks[id].content);
                });
            });
        });
    </script>
@endsection
@section('css')
    <style>
        .list_major {
            height: 300px;
            overflow: scroll;
            font-size: 18px
        }

        .dropdown-type {
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

        .type_checkbox {
            width: 25%;
        }
    </style>
@endsection
