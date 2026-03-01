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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('inviter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('is_active', ['active', 'inactive'])->default('active');
            $table->enum('is_approved', ['0', '1'])->default('0');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->integer('credit_score')->default(100);
            $table->timestamps();
            $table->softDeletes(); // This adds the 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
