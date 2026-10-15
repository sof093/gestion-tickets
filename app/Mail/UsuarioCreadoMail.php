<?php

namespace App\Mail;

use App\Models\Usuario; // Asegúrate de importar tu modelo
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioCreadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $passwordTemporal;

    public function __construct($usuario, $passwordTemporal)
    {
        $this->usuario = $usuario;
        $this->passwordTemporal = $passwordTemporal;
    }

    public function build()
    {
        return $this->subject('Bienvenido al Sistema - Datos de Acceso')
                    ->view('emails.usuario-creado');
    }
}