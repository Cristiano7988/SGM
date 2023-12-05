<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupDeExcluidos extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $tipo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $tipo)
    {
        $this->item = $item;
        $this->tipo = $tipo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.backup_de_excluidos');
    }
}
