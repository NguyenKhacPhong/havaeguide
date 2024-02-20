<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SchoolTypeController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminFeedbackController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Nhóm route xử lý trên addmin
Route::middleware('auth')->group(function () {
    Route::group(['prefix'=>'admin','as'=>'admin'],function () {
        Route::get('/', [DashboardController::class, 'show'])->name('.dashboard');
        Route::get('dashboard', [DashboardController::class,'show'])->name('.dashboard');
        Route::group(['prefix'=>'role', 'as'=> '.role'],function () {
            Route::get('/', [RoleController::class, 'index'])->name('.index');
            Route::get('list', [RoleController::class, 'index'])->name('.index');
            Route::get('create', [RoleController::class, 'create'])->name('.create');
            Route::post('store', [RoleController::class, 'store'])->name('.store');
        });
        Route::group(['prefix'=>'user', 'as'=> '.user'],function () {
            Route::get('/', [UserController::class, 'index'])->name('.index');
            Route::get('list', [UserController::class, 'index'])->name('.index');
            Route::get('list/{status}', [UserController::class, 'list'])->name('.status');
            Route::get('create', [UserController::class, 'create'])->name('.create');
            Route::post('store', [UserController::class, 'store'])->name('.store');
            Route::get('action', [UserController::class, 'action'])->name('.action');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [UserController::class, 'update'])->name('.update');
            Route::get('remove/{id}', [UserController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [UserController::class, 'restore'])->name('.restore');
            Route::get('delete/{id}', [UserController::class, 'delete'])->name('.delete');
        });
        Route::group(['prefix'=>'school', 'as'=> '.school'],function () {
            Route::get('/', [SchoolController::class, 'index'])->name('.index');
            Route::get('list', [SchoolController::class, 'index'])->name('.index');
            Route::get('list/{status}', [SchoolController::class, 'index'])->name('.status');
            Route::get('create', [SchoolController::class, 'create'])->name('.create');
            Route::post('store', [SchoolController::class, 'store'])->name('.store');
            Route::post('import', [SchoolController::class, 'import'])->name('.import');
            Route::post('export', [SchoolController::class, 'excelExport'])->name('.export');
            Route::post('action', [SchoolController::class, 'action'])->name('.action');
            Route::get('edit/{id}', [SchoolController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [SchoolController::class, 'update'])->name('.update');
            Route::get('remove/{id}', [SchoolController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SchoolController::class, 'restore'])->name('.restore');
            Route::get('delete/{id}', [SchoolController::class, 'delete'])->name('.delete');
            Route::get('benchmark', [SchoolController::class, 'benchmark'])->name('.benchmark');
        });
        Route::group(['prefix'=>'school_type', 'as'=> '.type'],function () {
            Route::get('/', [SchoolTypeController::class, 'index'])->name('.index');
            Route::post('store', [SchoolTypeController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [SchoolTypeController::class, 'delete'])->name('.delete');
            Route::get('edit', [SchoolTypeController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [SchoolTypeController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SchoolTypeController::class, 'restore'])->name('.restore');
            Route::post('update', [SchoolTypeController::class, 'update'])->name('.update');
        });

        Route::group(['prefix'=>'sector', 'as'=> '.sector'],function () {
            Route::get('/', [SectorController::class, 'index'])->name('.index');
            Route::post('create', [SectorController::class, 'create'])->name('.create');
            Route::post('store', [SectorController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [SectorController::class, 'delete'])->name('.delete');
            Route::get('edit', [SectorController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [SectorController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [SectorController::class, 'restore'])->name('.restore');
            Route::post('update', [SectorController::class, 'update'])->name('.update');
        });
        Route::group(['prefix'=>'major', 'as'=> '.major'],function () {
            Route::get('/', [MajorController::class, 'index'])->name('.index');
            Route::get('list/{status}', [MajorController::class, 'index'])->name('.status');
            Route::get('create', [MajorController::class, 'create'])->name('.create');
            Route::post('store', [MajorController::class, 'store'])->name('.store');
            Route::post('import', [MajorController::class, 'importExcel'])->name('.import');
            Route::post('export', [MajorController::class, 'excelExport'])->name('.export');
            Route::get('delete/{id}', [MajorController::class, 'delete'])->name('.delete');
            Route::get('edit/{id}', [MajorController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [MajorController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [MajorController::class, 'restore'])->name('.restore');
            Route::post('update/{id}', [MajorController::class, 'update'])->name('.update');
            Route::get('action', [MajorController::class, 'action'])->name('.action');
        });

        Route::group(['prefix'=>'question', 'as'=> '.question'],function () {
            Route::get('/', [QuestionController::class, 'index'])->name('.index');
            Route::get('list/{status}', [QuestionController::class, 'index'])->name('.status');
            Route::get('action', [QuestionController::class, 'action'])->name('.action');
            Route::get('view/{id}', [QuestionController::class, 'view'])->name('.view');
            Route::post('changeStatus/{id}', [QuestionController::class, 'changeStatus'])->name('.changeStatus');
            Route::post('store', [QuestionController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [QuestionController::class, 'delete'])->name('.delete');
            Route::get('edit/{$id}', [QuestionController::class, 'edit'])->name('.edit');
            Route::get('remove/{id}', [QuestionController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [QuestionController::class, 'restore'])->name('.restore');
            Route::post('update', [QuestionController::class, 'update'])->name('.update');
        });

        Route::group(['prefix'=>'page', 'as'=> '.page'],function () {
            Route::get('/', [PageController::class, 'index'])->name('.index');
            Route::get('list/{status}', [PageController::class, 'index'])->name('.status');
            Route::get('create', [PageController::class, 'create'])->name('.create');
            Route::post('store', [PageController::class, 'store'])->name('.store');
            Route::get('delete/{id}', [PageController::class, 'delete'])->name('.delete');
            Route::Get('edit/{id}', [PageController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [PageController::class, 'update'])->name('.update');
        });

        Route::group(['prefix'=>'post', 'as'=> '.post'],function () {
            Route::get('/', [PostController::class, 'index'])->name('.index');
            Route::get('list', [PostController::class, 'index'])->name('.index');
            Route::get('list/status', [PostController::class, 'index'])->name('.status');
            Route::get('action', [PostController::class, 'action'])->name('.action');
            Route::get('create', [PostController::class, 'create'])->name('.create');
            Route::get('edit/{id}', [PostController::class, 'edit'])->name('.edit');
            Route::post('update/{id}', [PostController::class, 'update'])->name('.update');
            Route::post('store', [PostController::class, 'store'])->name('.store');
            Route::get('remove/{id}', [PostController::class, 'remove'])->name('.remove');
            Route::get('restore/{id}', [PostController::class, 'restore'])->name('.restore');
            Route::get('delete/{id}', [PostController::class, 'delete'])->name('.delete');
        });
        Route::group(['prefix'=>'slider', 'as'=> '.slider'],function () {
            Route::get('', [SliderController::class, 'index'])->name('.index');
            Route::get('show', [SliderController::class, 'index'])->name('.index');
            Route::post('store', [SliderController::class, 'store'])->name('.store');
            Route::get('remove/{id}', [SliderController::class, 'remove'])->name('.remove');
            Route::get('delete/{id}', [SliderController::class, 'delete'])->name('.delete');
            Route::get('restore/{id}', [SliderController::class, 'restore'])->name('.restore');
        });
    });
    Route::redirect('/', 'admin');
});
