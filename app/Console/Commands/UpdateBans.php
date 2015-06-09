<?php

namespace Pushman\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Pushman\Ban;

class UpdateBans extends Command implements SelfHandling
{
    protected $description = 'Checks bans to see if they have passed their duration and deactivates them.';

    protected $name = 'pushman:bans';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $bans = Ban::where('active', 'yes')->get();

        foreach ($bans as $ban) {
            if ($ban->duration != '*') {
                $banned_at = $ban->created_at;
                $end_at = $banned_at->addDays($ban->duration);

                if ($end_at->isPast()) {
                    $ban->active = 'no';
                    $ban->save();
                }
            }
        }
    }
}
