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

            // چه کسی پرداخت کرده (کاربر، سازمان‌دهنده، ادمین — همگی user هستند)
            $table->foreignId('payer_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            // مبلغ قطعی پرداخت‌شده
            $table->unsignedBigInteger('amount');

            // روش پرداخت
            $table->enum('method', [
                'gateway',   // زرین‌پال، ملت، ...
                'wallet',    // کیف پول
                'admin',     // معاف یا پرداخت دستی
            ]);

            // زمان قطعی شدن پرداخت
            $table->timestamp('paid_at');

            // برای audit: این پرداخت از کدام attempt آمده (اختیاری)
            $table->foreignId('source_attempt_id')
                ->nullable()
                ->constrained('payment_attempts')
                ->nullOnDelete();

            $table->foreignId('performed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

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
