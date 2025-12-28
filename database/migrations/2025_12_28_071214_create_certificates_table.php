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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('certificate_holder_id')
                ->constrained()
                ->cascadeOnDelete();

            // اطلاعات گواهینامه
            $table->string('serial')->unique();
            $table->timestamp('issued_at')->nullable();


            $table->enum('status', ['draft', 'active', 'revoked'])
                ->default('active');


            $table->boolean('has_payment_issue')->default(false);

            $table->timestamps();

            // جلوگیری از صدور چند گواهینامه برای یک شخص در یک رویداد
            $table->unique(['event_id', 'certificate_holder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
