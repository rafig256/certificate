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
        Schema::create('event_signatory', function (Blueprint $table) {
            $table->id();
            // کلید خارجی برای جدول سازمان‌ها
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            // کلید خارجی برای جدول امضاکنندگان
            $table->foreignId('signatory_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_signatory');
    }
};
