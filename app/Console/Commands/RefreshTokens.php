<?php

namespace Pushman\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Pushman\Channel;

class RefreshTokens extends Command implements SelfHandling
{
    protected $description = 'Resets every token for every auto refresh channel.';

    protected $signature = 'pushman:refresh';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $channels = Channel::where('refreshes', 'yes')->get();

        foreach ($channels as $channel) {
            $channel->generateToken();
            $channel->save();
        }
    }
}
