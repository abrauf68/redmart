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
        Schema::create('user_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('method', ['bank'])->default('bank');

            //For BANK
            $table->string('bank_name')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('account_type', [
                'savings',
                'current',
                'salary',
                'fixed_deposit',
                'nri',
                'recurring_deposit',
                'demat',
                'others'
            ])->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bank_details');
    }
};
