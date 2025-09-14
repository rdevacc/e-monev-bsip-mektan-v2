<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->decimal('financial_target', 20, 2)->default(0)->nullable();
            $table->decimal('financial_realization', 20, 2)->default(0)->nullable();
            $table->decimal('physical_target', 20)->default(0)->nullable();
            $table->decimal('physical_realization', 20)->default(0)->nullable();
            $table->timestamp('period')->nullable();
            $table->json('completed_tasks')->nullable();
            $table->json('issues')->nullable(); 
            $table->json('follow_ups')->nullable();  
            $table->json('planned_tasks')->nullable(); 
            $table->foreignId('created_by')->default(2)->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->default(2)->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_activities');
    }
};
