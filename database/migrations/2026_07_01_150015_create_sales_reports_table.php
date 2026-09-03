<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('expert_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('outcome', ['won', 'lost', 'archived'])->nullable();
            $table->string('loss_reason')->nullable();
            $table->decimal('final_price', 14, 2)->nullable();
            $table->date('contract_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};