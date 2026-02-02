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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')
                ->constrained()
                ->cascadeOnDelete();

            // ارتباط با payment (اختیاری)
            $table->foreignId('payment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // مقدار تغییر (مثبت = واریز، منفی = برداشت)
            $table->bigInteger('amount');

            $table->enum('type', [
                'deposit',   // شارژ
                'withdraw',  // برداشت
                'refund',    // برگشت
                'adjustment' // اصلاح دستی (admin)
            ]);

            // توضیح انسانی
            $table->string('description')->nullable();

            $table->timestamps();

            $table->index('wallet_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
