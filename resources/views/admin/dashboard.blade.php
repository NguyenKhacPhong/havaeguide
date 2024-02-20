@extends('layouts/admin')
@section('content')
<div class="container-fluid pt-5 dashboard">
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
        <div class="col">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">Số người dùng</div>
                <div class="card-body">
                    <h5 class="card-title">{{$count_user}}</h5>
                    <p class="card-text">Số lượng người dùng đã đăng ký</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">Câu hỏi</div>
                <div class="card-body">
                    <h5 class="card-title">{{$count_question}}</h5>
                    <p class="card-text">Số lượng câu hỏi trên diễn đàn</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">Câu hỏi chưa duyệt</div>
                <div class="card-body">
                    <h5 class="card-title">{{$un_question}}</h5>
                    <p class="card-text">Số lượng câu hỏi chưa duyệt</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header">Trường học</div>
                <div class="card-body">
                    <h5 class="card-title">{{$count_school}}</h5>
                    <p class="card-text">Số trường học trong trang web</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end analytic  -->
    <div class="card">
        <div class="card-header font-weight-bold">
            Câu hỏi chưa phê duyệt
        </div>
        <div class="card-body">
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">Câu hỏi</th>
                        <th scope="col">Người hỏi</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($questions->total() > 0)
                    @foreach($questions as $question)
                    <tr class="">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$question->id}}">
                        </td>
                        <td style="width:55%; overflow:hidden;"><a href="#">{{$question->title}}</a></td>
                        <td >{{$question->user_name}}</td>
                        <td style="width:15%; overflow:hidden;"><a href="#">{{$question->created_at}}</a></td>
                        <td>
                            <a href="{{route('admin.question.view', $question->id)}}" class="btn {{$question->status==1 ? "btn-success" : "btn-warning"}} btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('admin.question.delete', $question->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn trường này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
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
            
        </div>
    </div>

</div>
@endsection

@section('css')
<style>
    .dashboard .card-header{
        text-transform: uppercase;
    }
</style>
@endsection