<?php

namespace App\Listeners;

use App\Events\AnonymousRegistered;
use App\Models\TotalUser;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUsersTotalCount
{
    use InteractsWithQueue;

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
    public function handle(AnonymousRegistered $event): void
    {
        TotalUser::increment('count');
    }
}
