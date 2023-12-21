<?php

namespace App\Mail;

use App\Models\Erro;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvisoDeErro extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $erro;

    /**
     * Create a new message instance.
     *
     * @param User|NULL
     * @param Erro
     * @return void
     */
    public function __construct($user, Erro $erro)
    {
        $this->user = $user;
        $this->erro = $erro;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.aviso_de_erro');
    }
}
