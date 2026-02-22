<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillGlobalWaiterNumbers extends Command
{
    protected $signature = 'waiters:backfill-global-numbers
                            {--dry-run : List waiters that would be updated without saving}';

    protected $description = 'Assign global_waiter_number to existing waiters who do not have one (so they can be found by manager search)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $waiters = User::role('waiter')->whereNull('global_waiter_number')->orderBy('id')->get();

        if ($waiters->isEmpty()) {
            $this->info('No waiters without a global number found.');

            return self::SUCCESS;
        }

        $this->info($dryRun
            ? 'Would assign global numbers to '.$waiters->count().' waiter(s):'
            : 'Assigning global numbers to '.$waiters->count().' waiter(s)...');

        foreach ($waiters as $waiter) {
            $number = User::generateGlobalWaiterNumber();
            $this->line("  {$waiter->name} (id: {$waiter->id}) → {$number}");
            if (! $dryRun) {
                $waiter->global_waiter_number = $number;
                $waiter->save();
            }
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('Run without --dry-run to apply changes.');
        } else {
            $this->info('Done. These waiters can now be found by manager search (Link Waiter).');
        }

        return self::SUCCESS;
    }
}
