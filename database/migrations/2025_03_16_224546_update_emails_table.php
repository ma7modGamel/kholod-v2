<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class UpdateEmailsTable extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('body'); // حقل "تمت القراءة"
            $table->unsignedBigInteger('user_id')->nullable()->after('is_read'); // ربط بالمستخدم
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['is_read', 'user_id']);
        });
    }
}