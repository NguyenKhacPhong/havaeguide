@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
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
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách các trang tĩnh</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">Slug</th>
                            <th scope="col">Tên trang</th>
                            <th scope="col">Ngày cập nhật</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($pages->total() > 0)
                            @foreach ($pages as $page)
                                <tr class="">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="{{ $page->id }}">
                                    </td>
                                    <td style="width:30%; overflow:hidden;">{{ $page->slug }}</td>
                                    <td style="width:40%; overflow:hidden;"><a href="#">{{ $page->name }}</a>
                                    </td>
                                    <td style="width:20%; overflow:hidden;">{{ $page->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.page.edit', $page->id) }}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ route('admin.page.delete', $page->id) }}"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh trang này không?')"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">Không tìm thấy bản ghi nào</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </form>
                <div>
                    {{ $pages->links() }}
                </div>

            </div>
        </div>
    @endsection
    @section('css')
        <style>
            #import_export_file form {
                display: inline;
            }
        </style>
    @endsection
