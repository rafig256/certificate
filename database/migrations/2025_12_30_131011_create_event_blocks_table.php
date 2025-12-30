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
        Schema::create('event_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            $table->enum('region', ['header', 'body', 'footer']);

            $table->string('type', 50);
            // sample: title | body_text | token | logo

            $table->enum('align', ['left', 'center', 'right'])->default('center');

            $table->unsignedSmallInteger('order')->default(0);

            $table->json('payload')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['event_id', 'region', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_blocks');
    }
};
