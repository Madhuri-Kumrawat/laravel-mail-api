<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BulkMailTask extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$data)
    {
        $this->subject=$subject;
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.laravel-test-mail')->subject($this->subject)
                        ->with(['body'=>$this->data['body']]);
        if(sizeof($this->data['attachemnts'])>0){
            foreach($this->data['attachemnts'] as $at){
                $base64 = $at['value']; // base64 encoded     
                $file_info=explode('.',$at['name']);
                $fileName = $file_info[0].''.date('m-d-Y_hia').'.'.$file_info[1];   
                
                Storage::disk('local')->put($fileName, base64_decode($base64));
                $email->attach(storage_path("app/".$fileName));
            }
        } 
        return $email;
    }
}
