<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddDefaultToMessageIdInEmailsTable extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('message_id')->default('N/A')->change();
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('message_id')->default(null)->change();
        });
    }
}
