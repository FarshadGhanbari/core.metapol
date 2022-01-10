<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('department', ['is', 'not'])->default('not');
            $table->timestamps();
        });
        Schema::connection('common_database')->create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::connection('common_database')->create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('common_database')->dropIfExists('roles');
        Schema::connection('common_database')->dropIfExists('permissions');
        Schema::connection('common_database')->dropIfExists('permission_role');
    }
}
