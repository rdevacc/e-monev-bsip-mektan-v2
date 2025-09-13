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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('work_group_id');
            $table->foreignId('work_team_id');
            $table->foreignId('status_id');
            $table->string('name')->nullable();
            $table->decimal('activity_budget', 16, 2)->default(0)->nullable();
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
        Schema::dropIfExists('activities');
    }
};
