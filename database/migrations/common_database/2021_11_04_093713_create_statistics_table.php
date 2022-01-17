<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\User::class)->nullable();
            $table->ipAddress('ip')->nullable();
            $table->json('geo');
            $table->string('referer_page')->nullable();
            $table->string('page');
            $table->string('device');
            $table->string('device_type');
            $table->string('platform');
            $table->string('platform_version');
            $table->string('browser');
            $table->string('browser_version');
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
        Schema::connection('common_database')->dropIfExists('statistics');
    }
}
