<?php

namespace App\Console\Commands;

use App\Models\BirthdayCard;
use Illuminate\Console\Command;

class GenerateBirthdayCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdays:generate';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate birthday cards for today\'s birthdays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = BirthdayCard::syncTodayForEligibleUsers();

        if ($count > 0) {
            $this->info("✅ Birthday cards generated for $count users");
        } else {
            $this->info('ℹ️ No new birthday cards needed for today');
        }

        return 0;
    }
}
