<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class UnsubscribeSchool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unsubscribe:school';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unsubscribe schools after 3 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Subscription::where('status', 'active')
            ->whereDate('ends_at', '<', now())
            ->update(['status' => 'expired']);
    }
}
