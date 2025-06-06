<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoices;
use App\Mail\SendInvoiceMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:payment_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description : send a payment reminder after the payment deadline has passed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today(); 
        $invoices = Invoices::with(['receiver', 'sender.organizations', 'companypriceplans.priceplan'])->where('invoice_status', 'sent')->whereIn('payment_status', ['pending', 'failed'])->whereDate('payment_due_date', '<', $today)->get();

        foreach ($invoices as $invoice) {
            $last_reminder = $invoice->last_reminder_date ? Carbon::parse($invoice->last_reminder_date) : null;

            if (is_null($last_reminder) && is_null($invoice->payment_reminder)) {
                $invoice->update([
                    'last_reminder_date' => $today->format('Y-m-d'),
                    'payment_reminder' => 1
                ]);

                Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoice));
            }

            elseif ($last_reminder && $last_reminder->lt($today)) {
                $invoice->update([
                    'last_reminder_date' => $today->format('Y-m-d'),
                    'payment_reminder' => $invoice->payment_reminder + 1
                ]);

                Mail::to('bhavin.virtueinfo@gmail.com')->send(new SendInvoiceMail($invoice));
            }
        }

        $this->info("Send a payment reminder successfully.");
    }
}
