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
        Schema::create('pool_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('pool_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('is_paid')->default(false);

            $table->unique(['user_id', 'pool_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pool');
    }
};
