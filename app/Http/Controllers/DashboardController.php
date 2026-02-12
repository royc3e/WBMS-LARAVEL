<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $summaryMetrics = [
            [
                'label' => 'Total Consumers',
                'value' => 0,
                'change' => '+0%',
                'description' => 'Sum of registered households',
                'icon' => 'users',
            ],
            [
                'label' => 'Active Service Connections',
                'value' => 0,
                'change' => '+0',
                'description' => 'Connections with an active status',
                'icon' => 'bolt',
            ],
            [
                'label' => 'Bills Generated This Month',
                'value' => 0,
                'change' => '0 pending',
                'description' => 'Issued invoices for the current cycle',
                'icon' => 'document',
            ],
            [
                'label' => 'Payments Collected',
                'value' => '₱0.00',
                'change' => '0 receipts',
                'description' => 'Payments recorded this month',
                'icon' => 'wallet',
            ],
        ];

        $modules = [
            [
                'name' => 'Meter Reading',
                'icon' => 'gauge',
                'accent' => 'bg-blue-500/10 text-blue-600 dark:text-blue-300',
                'summary' => 'Capture and review monthly water consumption per household.',
                'stats' => [
                    ['label' => 'Consumption Records', 'value' => 0],
                    ['label' => 'Pending Validations', 'value' => 0],
                    ['label' => 'Latest Reading', 'value' => '—'],
                ],
                'links' => [
                    ['label' => 'View Readings', 'href' => $this->routeOrFallback('meter-readings.index')],
                    ['label' => 'Add Reading', 'href' => $this->routeOrFallback('meter-readings.create')],
                ],
            ],
            [
                'name' => 'Consumer Information',
                'icon' => 'users',
                'accent' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-300',
                'summary' => 'Maintain consumer profiles, service addresses, and account states.',
                'stats' => [
                    ['label' => 'Active Accounts', 'value' => 0],
                    ['label' => 'Disconnected', 'value' => 0],
                    ['label' => 'Pending Applications', 'value' => 0],
                ],
                'links' => [
                    ['label' => 'Manage Consumers', 'href' => $this->routeOrFallback('consumers.index')],
                    ['label' => 'Add Consumer', 'href' => $this->routeOrFallback('consumers.create')],
                ],
            ],
            [
                'name' => 'Rates & Charges',
                'icon' => 'scale',
                'accent' => 'bg-amber-500/10 text-amber-600 dark:text-amber-300',
                'summary' => 'Configure base rates, tiered consumption brackets, and installation fees.',
                'stats' => [
                    ['label' => 'Rate Tiers', 'value' => 0],
                    ['label' => 'Minimum Consumption', 'value' => '—'],
                    ['label' => 'Installation Fees', 'value' => 0],
                ],
                'links' => [
                    ['label' => 'View Rate Matrix', 'href' => $this->routeOrFallback('rates.index')],
                    ['label' => 'Update Charges', 'href' => $this->routeOrFallback('charges.edit')],
                ],
            ],
            [
                'name' => 'Billing & Payments',
                'icon' => 'receipt',
                'accent' => 'bg-purple-500/10 text-purple-600 dark:text-purple-300',
                'summary' => 'Generate consumer invoices and reconcile payments received.',
                'stats' => [
                    ['label' => 'Open Bills', 'value' => 0],
                    ['label' => 'Overdue Accounts', 'value' => 0],
                    ['label' => 'Collected Today', 'value' => '₱0.00'],
                ],
                'links' => [
                    ['label' => 'Billing Runs', 'href' => $this->routeOrFallback('billings.index')],
                    ['label' => 'Record Payment', 'href' => $this->routeOrFallback('payments.create')],
                ],
            ],
            [
                'name' => 'User Management',
                'icon' => 'shield',
                'accent' => 'bg-slate-500/10 text-slate-600 dark:text-slate-300',
                'summary' => 'Control administrator and staff accounts, roles, and permissions.',
                'stats' => [
                    ['label' => 'Admins', 'value' => 0],
                    ['label' => 'Staff', 'value' => 0],
                    ['label' => 'Pending Invites', 'value' => 0],
                ],
                'links' => [
                    ['label' => 'Manage Users', 'href' => $this->routeOrFallback('users.index')],
                    ['label' => 'Roles & Permissions', 'href' => $this->routeOrFallback('roles.index')],
                ],
            ],
            [
                'name' => 'Reports',
                'icon' => 'chart',
                'accent' => 'bg-rose-500/10 text-rose-600 dark:text-rose-300',
                'summary' => 'Access consolidated operational insights and export billing reports.',
                'stats' => [
                    ['label' => 'Published Reports', 'value' => 0],
                    ['label' => 'Scheduled', 'value' => 0],
                    ['label' => 'Last Export', 'value' => '—'],
                ],
                'links' => [
                    ['label' => 'View Reports', 'href' => $this->routeOrFallback('reports.index')],
                    ['label' => 'Generate Report', 'href' => $this->routeOrFallback('reports.create')],
                ],
            ],
        ];

        $recordsOverview = collect($modules)->map(function ($module) {
            $total = collect($module['stats'])
                ->filter(fn ($stat) => is_numeric($stat['value']))
                ->sum('value');

            return [
                'name' => $module['name'],
                'summary' => $module['summary'],
                'total' => $total,
                'primary_link' => $module['links'][0] ?? null,
                'accent' => $module['accent'],
                'icon' => $module['icon'],
            ];
        });

        return view('dashboard', [
            'summaryMetrics' => $summaryMetrics,
            'modules' => $modules,
            'recordsOverview' => $recordsOverview,
            'refreshedAt' => now(),
        ]);
    }

    private function routeOrFallback(string $name, string $fallback = '#'): string
    {
        return Route::has($name) ? route($name) : $fallback;
    }
}
