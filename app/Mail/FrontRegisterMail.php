<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FrontRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $isSuccess, $logo, $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->logo = asset('images/logo.png');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->data['email'], 'Ditstek')
        ->subject('Your Registration is Successful!!!')
        ->view('FrontRegisterMail',['name'=>$this->data['first_name'].' '.$this->data['last_name'], 'logo' => $this->logo]);
    }
}