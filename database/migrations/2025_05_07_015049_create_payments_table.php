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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 10, 2);
            $table->dateTime('date');
            $table->foreignId('id_project')->constrained('projects');
            $table->foreignId('id_user')->constrained('users');
            $table->string('id_preference')->nullable();
            $table->foreignId('id_reward')->constrained('rewards');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
