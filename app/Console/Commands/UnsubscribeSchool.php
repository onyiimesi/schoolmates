<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

#[\Illuminate\Console\Attributes\Description('Unsubscribe schools after 3 months')]
#[\Illuminate\Console\Attributes\Signature('unsubscribe:school')]
class UnsubscribeSchool extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Subscription::where('status', 'active')
            ->whereDate('ends_at', '<', now())
            ->update(['status' => 'expired']);
    }
}
