<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('countries', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\Country::class);
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\Province::class);
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
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
        Schema::connection('common_database')->dropIfExists('countries');
        Schema::connection('common_database')->dropIfExists('provinces');
        Schema::connection('common_database')->dropIfExists('cities');
    }
}
