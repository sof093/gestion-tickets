<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->correo,
        ], false));

        return (new MailMessage)
            ->subject('Restablecer contraseña - Grupo TRACOMEX')
            ->line('Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer contraseña', $url)
            ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos.')
            ->line('Si no solicitaste un cambio de contraseña, no se requiere ninguna acción adicional.');
    }
}