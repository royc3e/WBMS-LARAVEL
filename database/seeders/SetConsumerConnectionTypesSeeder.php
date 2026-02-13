<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetConsumerConnectionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all consumers with null connection_type to 'residential' as default
        $updated = DB::table('consumers')
            ->whereNull('connection_type')
            ->update(['connection_type' => 'residential']);

        $this->command->info("Updated {$updated} consumer(s) with default connection type 'residential'");

        // Show current distribution
        $residential = DB::table('consumers')->where('connection_type', 'residential')->count();
        $commercial = DB::table('consumers')->where('connection_type', 'commercial')->count();
        $null = DB::table('consumers')->whereNull('connection_type')->count();

        $this->command->info("\nConnection Type Distribution:");
        $this->command->info("Residential: {$residential}");
        $this->command->info("Commercial: {$commercial}");
        $this->command->info("Not Set: {$null}");
    }
}
