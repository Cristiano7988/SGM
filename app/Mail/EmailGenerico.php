<?php

namespace App\Mail;

use App\Helpers\Substitui;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailGenerico extends Mailable
{
    use Queueable, SerializesModels;

    public $conteudo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $user, $conteudo, $anexo = null)
    {
        $conteudo = Substitui::masAntesChecaSePrecisa($request, $conteudo);
        $conteudo = str_replace('{{anexo}}', env('APP_URL') . '/storage/' . $anexo, $conteudo);

        $this->conteudo = $conteudo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.generico');
    }
}
