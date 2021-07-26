<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendaftaranAkun extends Mailable
{
    use Queueable, SerializesModels;

    public $akun_otp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($akun_otp)
    {
        $this->akun_otp = $akun_otp;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Berhasil Membuat Akun')
                ->markdown('emails.sites.akun_otp');
    }
}
