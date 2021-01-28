<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\BulkMailTask;
use Mail;
use Illuminate\Support\Facades\Storage;


class SendBulkEmailTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new BulkMailTask();
        $data=$this->details;
        foreach ($data as $value) {           
            $mdata=$value; 
            Mail::to($value['toEmail'])->send(new BulkMailTask($value['subject'],$value));
        }
    }
}
