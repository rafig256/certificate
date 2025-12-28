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
        Schema::create('certificate_holders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');

            $table->string('national_code', 10)->unique()->nullable();
            $table->string('mobile', 11)->unique()->nullable();
            $table->string('email')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();

            $table->index('national_code');
            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_holders');
    }
};
