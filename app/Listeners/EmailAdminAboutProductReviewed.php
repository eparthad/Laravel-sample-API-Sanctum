<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ProductReviewed;
use App\Mail\ReviewedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailAdminAboutProductReviewed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProductReviewed $event)
    {
        $admin = User::select('email')->firstOrFail();
        
        Mail::to($admin->email)->send(new ReviewedMail($event));
    }
}
