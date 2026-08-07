<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'purchase_stage')) {
                $table->dropColumn('purchase_stage');
            }
            if (Schema::hasColumn('projects', 'project_level')) {
                $table->dropColumn('project_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('purchase_stage', 20)->nullable()->after('purchase_status');
            $table->string('project_level', 10)->nullable()->after('level');
        });
    }
};
