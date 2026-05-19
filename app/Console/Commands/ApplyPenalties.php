<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplyPenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:apply-penalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply 5% penalty to bills that are overdue by more than 5 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $graceDays = 5;
        $penaltyRate = 0.05;

        // Bills that are pending, overdue, or partial, due date was > 5 days ago, and NO penalty has been applied yet.
        $targetDate = Carbon::now()->subDays($graceDays)->toDateString();

        $billings = Billing::whereIn('status', ['pending', 'overdue', 'partial'])
            ->whereDate('due_date', '<', $targetDate)
            ->where('penalty', 0)
            ->get();

        $count = 0;
        foreach ($billings as $bill) {
            // Apply 5% to the remaining unpaid balance of the bill.
            // The remaining balance BEFORE penalty is:
            $remaining = $bill->amount + $bill->arrears - $bill->total_paid;

            if ($remaining > 0) {
                $penalty = $remaining * $penaltyRate;
                
                $bill->update([
                    'penalty' => $penalty,
                    'status' => 'overdue',
                    'notes' => $bill->notes . "\n[Penalty of 5% applied for overdue balance]"
                ]);
                $count++;
            }
        }

        $this->info("Applied 5% penalty to {$count} overdue bills.");
    }
}
