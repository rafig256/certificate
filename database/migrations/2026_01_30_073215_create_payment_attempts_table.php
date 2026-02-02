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
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('payment_id')->nullable();


            // چه کسی این تلاش را شروع کرده
            $table->foreignId('payer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // مبلغی که تلاش شده پرداخت شود
            $table->unsignedBigInteger('amount');

            // درگاه پرداخت
            $table->string('gateway'); // zarinpal, mellat, ...

            // authority / token قبل از پرداخت
            $table->string('authority')->nullable();

            // ref_id بعد از پرداخت موفق
            $table->string('ref_id')->nullable()->unique();

            $table->enum('status', [
                'initiated',
                'redirected',
                'verified',
                'failed',
                'canceled',
                'timeout',
            ]);

            // payload خام درگاه (برای debug و dispute)
            $table->json('gateway_payload')->nullable();

            $table->timestamps();

            $table->index(['gateway', 'authority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
