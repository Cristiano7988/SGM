<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TodasTransacoes extends Mailable
{
    use Queueable, SerializesModels;

    public $transacoes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transacoes)
    {
        $this->transacoes = $transacoes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('mails.transacoes');
    }
}
