<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            if (!Schema::hasColumn('billings', 'previous_balance')) {
                $table->decimal('previous_balance', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('billings', 'penalty')) {
                $table->decimal('penalty', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('billings', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            if (Schema::hasColumn('billings', 'previous_balance')) {
                $table->dropColumn('previous_balance');
            }
            if (Schema::hasColumn('billings', 'penalty')) {
                $table->dropColumn('penalty');
            }
            if (Schema::hasColumn('billings', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
