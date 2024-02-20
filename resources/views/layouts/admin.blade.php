<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo1.png')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Quản trị cửa hàng</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">Hava E-guide Admin</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{url('admin/post/create')}}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{url('admin/school/create')}}">Thêm trường</a>
                        <a class="dropdown-item" href="{{url('admin/question')}}">Duyệt câu hỏi</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Tài khoản</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->

        @php
            $module_active = session('module_active')
        @endphp
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{$module_active == 'dashboard' ? 'active': ''}}">
                        <a href="{{url('/admin/dashboard')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-solid fa-gauge-high"></i>
                            </div>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-link {{$module_active == 'page' ? 'active': ''}}">
                        <a href="{{url('/admin/page')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </div>
                            Trang tĩnh
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{url('/admin/page/create')}}">Thêm mới</a></li>
                            <li><a href="{{url('/admin/page')}}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'slider' ? 'active': ''}}">
                        <a href="{{url('/admin/slider')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-duotone fa-film-simple"></i>
                            </div>
                            Slider
                        </a>
                    </li>
                    <li class="nav-link {{$module_active == 'school' ? 'active': ''}}">
                        <a href="{{route('admin.school.index')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fas fa-gears"></i>
                            </div>
                            Trường học
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{route('admin.school.create')}}">Thêm mới</a>
                            </li>
                            <li><a href="{{route('admin.school.index')}}">Danh sách</a></li>
                            <li><a href="{{route('admin.school.index')}}">Thêm điểm chuẩn</a></li>
                            <li><a href="{{route('admin.type.index')}}">Hệ đào tạo</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'major' ? 'active': ''}}">
                        <a href="{{route('admin.major.index')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-solid fa-copyright"></i>
                            </div>
                            Ngành đào tạo
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{route('admin.major.create')}}">Thêm mới</a>
                            </li>
                            <li><a href="{{route('admin.major.index')}}">Danh sách</a></li>
                            <li><a href="{{route('admin.sector.index')}}">Nhóm ngành</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'order' ? 'active': ''}}">
                        <a href="{{route('admin.question.index')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            Hỏi đáp
                        </a>
                    </li>
                    <li class="nav-link {{$module_active == 'post' ? 'active': ''}}">
                        <a href="{{url('/admin/post')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-solid fa-message"></i>
                            </div>
                            Tin tức
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{route('admin.post.create')}}">Thêm mới</a>
                            </li>
                            <li><a href="{{route('admin.post.index')}}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'user' ? 'active': ''}}">
                        <a href="{{url('/admin/user/list')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            Người dùng
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{url('/admin/user/create')}}">Thêm mới</a></li>
                            <li><a href="{{url('admin/user/list')}}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{$module_active == 'role' ? 'active': ''}}">
                        <a href="{{url('/admin/role')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="fa-duotone fa-user-group"></i>
                            </div>
                            Nhóm quyền
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{url('/admin/role/create')}}">Thêm mới</a></li>
                            <li><a href="{{url('/admin/role/list')}}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link">
                        <a>
                            <div class="nav-link-icon d-inline-flex">
                            <i class="fa-light fa-solar-system"></i>
                        </div>
                        Hệ thống
                        </a>
                    </li>

                </ul>
            </div>
            <div id="wp-content">
                @yield('content')
            </div>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{asset('js/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
    <script src="{{asset('js/app.js')}}"></script>

    @yield('js')
    @yield('css')
</body>

</html>
