<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkMailTask extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.laravel-test-mail');
        // return $this->from('example@example.com')
        //         ->view('emails.laravel-test-mail')
        //         ->with([
        //             'orderName' => $this->order->name,
        //             'orderPrice' => $this->order->price,
        //         ])
        //         ->attach('/path/to/file', [
        //             'as' => 'name.pdf',
        //             'mime' => 'application/pdf',
        //         ]);
    }
}
