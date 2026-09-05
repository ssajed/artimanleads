<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * اضافه کردن ستون‌های حیاتی که فاقد Migration رسمی هستند.
     * این متد Idempotent است و ستون‌های موجود را دوباره ایجاد نمی‌کند.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // 1. floors - بعد از blocks_count
            if (!Schema::hasColumn('projects', 'floors')) {
                $table->integer('floors')->nullable()->default(null)->after('blocks_count');
            }

            // 2. blocks - بعد از floors
            if (!Schema::hasColumn('projects', 'blocks')) {
                $table->integer('blocks')->nullable()->default(null)->after('floors');
            }

            // 3. level - بعد از project_status
            if (!Schema::hasColumn('projects', 'level')) {
                $table->enum('level', ['A', 'B', 'C'])->nullable()->default(null)->after('project_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Intentionally left empty.
     * These columns may have existed before this migration in Production.
     * Rolling back could accidentally drop critical data columns.
     */
    public function down(): void
    {
        //
    }
};
