<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecimiento de Contraseña</title>
    <style>
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .logo {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <p>Hola {{ $username }},</p>
    <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
    <a href="{{ $resetLink }}">Restablecer Contraseña</a>

    <!-- Imagen adjunta -->
    <div>
        <img src="{{ $message->embed(public_path('images/logo_caminoreal.png')) }}" alt="Administración Camino Real" class="logo">
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} Administración Camino Real. Todos los derechos reservados.</p>
    </div>
</body>
</html>
