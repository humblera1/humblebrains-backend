<?php

namespace App\Listeners;

use App\Models\TotalUser;
use Illuminate\Auth\Events\Registered;
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
    public function handle(Registered $event): void
    {
        TotalUser::increment('count');
    }
}
