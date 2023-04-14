<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\UserService;
use App\Events\UserSaved;

class SaveUserBackgroundInformation
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected UserService $userService
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->storeUserDetails($event->user);
    }
}
