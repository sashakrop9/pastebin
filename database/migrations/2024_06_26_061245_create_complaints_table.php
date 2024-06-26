<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('paste_id');
            $table->text('reason');
            $table->string('status')->default('pending'); // по умолчанию статус жалобы ожидает рассмотрения
            $table->timestamps();

            // Внешние ключи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('paste_id')->references('id')->on('pastes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
