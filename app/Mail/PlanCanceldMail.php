<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanCanceldMail extends Mailable
{
    use Queueable, SerializesModels;

    public $priceplans;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($priceplans)
    {
        $this->priceplans = $priceplans;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('BoTrak Plan Cancellation Notice')->view('emails.plancancel');
    }
}
