<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // فقط فیلدهایی که وجود ندارند اضافه شوند
            if (!Schema::hasColumn('projects', 'mechanical_consultant_mobile')) {
                $table->string('mechanical_consultant_mobile', 20)->nullable();
            }
            
            if (!Schema::hasColumn('projects', 'hvac_contractor_mobile')) {
                $table->string('hvac_contractor_mobile', 20)->nullable();
            }
            
            if (!Schema::hasColumn('projects', 'has_chiller')) {
                $table->enum('has_chiller', ['yes', 'no'])->nullable();
            }
            
            if (!Schema::hasColumn('projects', 'chiller_photo')) {
                $table->string('chiller_photo')->nullable();
            }
            
            if (!Schema::hasColumn('projects', 'has_cooling_tower')) {
                $table->enum('has_cooling_tower', ['yes', 'no'])->nullable();
            }
            
            if (!Schema::hasColumn('projects', 'cooling_tower_photo')) {
                $table->string('cooling_tower_photo')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'mechanical_consultant_mobile',
                'hvac_contractor_mobile',
                'has_chiller',
                'chiller_photo',
                'has_cooling_tower',
                'cooling_tower_photo'
            ]);
        });
    }
};