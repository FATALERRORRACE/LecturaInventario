<?php

namespace App\Listeners;

use App\Events\TablesProcessed;
use App\Jobs\ProcessBiblioteca;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTablesProcessed implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TablesProcessed $event): void
    {
        //
    }
}
