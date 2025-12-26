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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced']);
            $table->foreignId('organizer_id')->constrained('organizations')->cascadeOnDelete();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->text('certificate_text');
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->string('location');
            $table->string('link')->nullable();
            $table->enum('status', [
                'Draft',
                'PendingPayment',
                'Active',
                'Completed',
                'Canceled',
            ])->default('Draft');
            $table->enum('payment_mode', ['OrganizerPays','ParticipantPays','Free']);
            $table->unsignedBigInteger('price_per_person')->default(0);
            $table->boolean('has_exam')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
