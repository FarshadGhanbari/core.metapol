<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('common_database')->create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('business_areas', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('business_area_business_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\BusinessCategory::class);
            $table->foreignIdFor(\App\Models\Shared\BusinessArea::class);
        });
        Schema::connection('common_database')->create('business_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\BusinessArea::class);
            $table->enum('status', ['disable', 'active'])->default('disable');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::connection('common_database')->create('business_subbranches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Shared\BusinessBranch::class);
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
        Schema::connection('common_database')->dropIfExists('business_categories');
        Schema::connection('common_database')->dropIfExists('business_areas');
        Schema::connection('common_database')->dropIfExists('business_branches');
        Schema::connection('common_database')->dropIfExists('business_subbranches');
    }
}
