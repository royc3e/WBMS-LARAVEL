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
        Schema::create('water_rate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->decimal('value', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('water_rate_settings')->insert([
            [
                'key' => 'minimum_rate',
                'label' => 'Minimum Rate',
                'value' => 200.00,
                'description' => 'Base charge for consumption up to minimum cubic meters',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'minimum_consumption',
                'label' => 'Minimum Consumption',
                'value' => 10.00,
                'description' => 'Cubic meters included in minimum rate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'residential_excess_rate',
                'label' => 'Residential Excess Rate',
                'value' => 15.00,
                'description' => 'Rate per cubic meter beyond minimum (Residential)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'commercial_excess_rate',
                'label' => 'Commercial Excess Rate',
                'value' => 20.00,
                'description' => 'Rate per cubic meter beyond minimum (Commercial)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'industrial_excess_rate',
                'label' => 'Industrial Excess Rate',
                'value' => 25.00,
                'description' => 'Rate per cubic meter beyond minimum (Industrial)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'government_excess_rate',
                'label' => 'Government Excess Rate',
                'value' => 12.00,
                'description' => 'Rate per cubic meter beyond minimum (Government)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_rate_settings');
    }
};
