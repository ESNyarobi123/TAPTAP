<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Table;

class GenerateServiceTags extends Command
{
    protected $signature = 'tags:generate';
    protected $description = 'Generate service tags for existing restaurants, waiters, and tables';

    public function handle()
    {
        $this->info('Starting tag generation...');

        // 1. Generate Restaurant Prefixes
        $restaurants = Restaurant::whereNull('tag_prefix')->get();
        foreach ($restaurants as $restaurant) {
            $restaurant->tag_prefix = $restaurant->generateUniqueTagPrefix();
            $restaurant->save();
            $this->info("Generated prefix {$restaurant->tag_prefix} for {$restaurant->name}");
        }

        // 2. Generate Waiter Codes
        $waiters = User::role('waiter')->whereNull('waiter_code')->get();
        foreach ($waiters as $waiter) {
            if ($waiter->restaurant && $waiter->restaurant->tag_prefix) {
                $waiter->waiter_code = $waiter->restaurant->generateWaiterCode();
                $waiter->save();
                $this->info("Generated code {$waiter->waiter_code} for waiter {$waiter->name}");
            }
        }

        // 3. Generate Table Tags
        $tables = Table::withoutGlobalScopes()->whereNull('table_tag')->get();
        foreach ($tables as $table) {
            if ($table->restaurant && $table->restaurant->tag_prefix) {
                $table->table_tag = $table->restaurant->generateTableTag();
                $table->save();
                $this->info("Generated tag {$table->table_tag} for table {$table->name}");
            }
        }

        $this->info('All tags generated successfully!');
    }
}
