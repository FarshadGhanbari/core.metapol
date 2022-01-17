<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\User::class)->nullable();
            $table->ipAddress('user_ip');
            $table->morphs('loggable');
            $table->string('action');
            $table->json('values')->nullable();
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
        Schema::connection('common_database')->dropIfExists('logs');
    }
}
