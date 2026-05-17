<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify the enum values on the status column to include 'void' and 'partial'
        DB::statement("ALTER TABLE billings MODIFY COLUMN status ENUM('pending', 'paid', 'overdue', 'cancelled', 'void', 'partial') NOT NULL DEFAULT 'pending'");

        // 2. Safely transition existing 'cancelled' entries to 'void'
        DB::table('billings')->where('status', 'cancelled')->update(['status' => 'void']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert 'void' and 'partial' records back to 'cancelled' or 'pending'
        DB::table('billings')->where('status', 'void')->update(['status' => 'cancelled']);
        DB::table('billings')->where('status', 'partial')->update(['status' => 'pending']);

        // Revert the column definition
        DB::statement("ALTER TABLE billings MODIFY COLUMN status ENUM('pending', 'paid', 'overdue', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
