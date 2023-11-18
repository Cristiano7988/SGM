<?php

namespace App\Mail;

use App\Models\Transacao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
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
    public function __construct($request)
    {
        $transacoes = Transacao::all()->whereIn('id', explode(',', $request->ids));
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
