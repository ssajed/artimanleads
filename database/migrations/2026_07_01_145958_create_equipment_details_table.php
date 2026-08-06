<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('type', ['chiller', 'cooling_tower']);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('nameplate_photo_path')->nullable();
            $table->timestamps();
            
            // جلوگیری از ثبت تکراری برای یک پروژه
            $table->unique(['project_id', 'type']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_details');
    }
};