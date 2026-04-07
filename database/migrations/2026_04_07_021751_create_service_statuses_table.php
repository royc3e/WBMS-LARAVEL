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
        Schema::create('service_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumer_id')->constrained('consumers')->onDelete('cascade');
            $table->enum('status', ['active', 'disconnected']);
            $table->enum('action_type', ['disconnection', 'reconnection']);
            $table->string('reason');
            $table->date('status_date');
            $table->foreignId('processed_by')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['consumer_id', 'status_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_statuses');
    }
};
