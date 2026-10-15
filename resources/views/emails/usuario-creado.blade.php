<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #334155; padding: 20px; }
        .card { background: #ffffff; padding: 24px; border-radius: 12px; max-width: 500px; margin: 0 auto; border: 1px solid #e2e8f0; }
        .header { font-size: 18px; font-weight: bold; color: #0f172a; margin-bottom: 12px; }
        .badge { background-color: #f1f5f9; padding: 12px; border-radius: 8px; font-family: monospace; font-size: 16px; font-weight: bold; color: #2563eb; display: inline-block; margin: 10px 0; }
        .footer { font-size: 11px; color: #64748b; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">¡Hola, {{ $usuario->nombre_completo }}!</div>
        <p>Se ha creado tu cuenta en el sistema de <strong>Grupo TRACOMEX</strong>. A continuación se muestran tus credenciales de acceso temporal:</p>
        
        <p><strong>Número de Nómina / Usuario:</strong> {{ $usuario->num_nomina }}</p>
        <p><strong>Contraseña Temporal:</strong></p>
        <div class="badge">{{ $passwordTemporal }}</div>

        <p style="font-size: 12px; color: #ef4444;">* Por seguridad, te recomendamos cambiar tu contraseña la primera vez que ingreses al sistema.</p>

        <div class="footer">
            Este es un correo automático, por favor no respondas a este mensaje.<br>
            © Grupo TRACOMEX - Control de Acceso
        </div>
    </div>
</body>
</html>