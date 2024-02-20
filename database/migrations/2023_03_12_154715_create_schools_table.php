<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id'); // Khóa chính của bảng, tự động tăng
            $table->string('school_code')->unique()->nullable(false); // Mã trường, duy nhất, không được phép rỗng
            $table->string('school_name', 200)->unique()->nullable(false); // Tên trường, tối đa 200 ký tự, duy nhất, không được phép rỗng
            $table->string('school_address', 500)->nullable(false); // Địa chỉ trường, tối đa 500 ký tự, không được phép rỗng
            $table->string('school_phone', 20)->nullable(); // Số điện thoại của trường, tối đa 20 ký tự, có thể rỗng
            $table->string('school_email', 100)->nullable(); // Địa chỉ email của trường, tối đa 100 ký tự, có thể rỗng
            $table->string('school_website', 200)->nullable(); // Đường dẫn trang web của trường, tối đa 200 ký tự, có thể rỗng
            $table->text('majors')->nullable();
            $table->text('school_description')->nullable(); // Mô tả về trường, có thể rỗng
            $table->text('school_image')->nullable(); // Đường dẫn đến hình ảnh của trường, có thể rỗng
            $table->text('school_logo')->nullable(); // Đường dẫn đến hình ảnh của trường, có thể rỗng
            $table->unsignedInteger('type_id'); // Khóa ngoại của bảng "school_types"
            $table->unsignedInteger('area_id'); // Khóa ngoại của bảng "areas"
            $table->timestamps(); // Thêm cột "created_at" và "updated_at" để lưu thời gian tạo và cập nhật bản ghi
            $table->softDeletes(); // Thêm cột "deleted_at" để xóa mềm bản ghi
            $table->foreign('type_id')->references('id')->on('school_types'); // Thêm khóa ngoại tới bảng "school_types"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
};
