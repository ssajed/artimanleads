<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // اطلاعات پایه
            $table->string('title'); // نام پروژه
            $table->date('visit_date'); // تاریخ بازدید
            $table->text('address'); // آدرس
            $table->string('region')->nullable(); // منطقه
            $table->json('usage_type')->nullable(); // کاربری‌ها
            $table->integer('floors_count')->nullable(); // تعداد طبقات
            $table->integer('blocks_count')->nullable(); // تعداد بلوک‌ها
            
            // وضعیت تجهیزات
            $table->boolean('chiller_selected')->default(false);
            $table->string('chiller_brand')->nullable();
            $table->boolean('cooling_tower_selected')->default(false);
            $table->string('current_cooling_brand')->nullable();
            $table->decimal('capacity_tr', 10, 2)->nullable();
            
            // وضعیت خرید
            $table->enum('purchase_status', ['no_inquiry', 'inquiry', 'negotiation', 'purchased'])->default('no_inquiry');
            $table->date('estimated_purchase_date')->nullable();
            $table->enum('project_level', ['A_hot', 'B_followup', 'C_archive'])->default('B_followup');
            $table->text('notes')->nullable();
            
            // ارتباط با کاربران
            $table->foreignId('marketer_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};