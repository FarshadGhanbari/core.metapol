<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilemanagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('filemanagers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('src');
            $table->timestamps();
        });
        Schema::connection('common_database')->create('filemanager_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filemanager_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('src');
            $table->string('name');
            $table->timestamps();
        });
        Schema::connection('common_database')->create('filemanager_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filemanager_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('filemanager_folder_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('src');
            $table->string('name');
            $table->string('extension');
            $table->string('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('common_database')->dropIfExists('filemanagers');
        Schema::connection('common_database')->dropIfExists('filemanager_folders');
        Schema::connection('common_database')->dropIfExists('filemanager_files');
    }
}
