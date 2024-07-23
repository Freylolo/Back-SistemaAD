<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .divider {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #000080;
        }
        .footer img {
            max-width: 10px; 
            display: block;
            margin-top: 10px;
        }
        .footer p {
            font-size: 8px; 
            color: #000080; 
            margin: 5px 0; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $subject }}</h1>
        <p>{{ $defaultMessage }}</p>
        <p>{{ $text }}</p>
        <hr class="divider">
        <div class="footer">
            <p>Este correo es generado automáticamente por el Sistema Administrativo.</p>
            <p>La validez de la invitación QR es de 24 horas.</p>
            <p>La administración no se responsabiliza por el uso indebido de los códigos QR.</p>
            <img src="{{ $message->embed(public_path('images/logo_caminoreal.png')) }}" alt="Administración Camino Real">
        </div>
    </div>
</body>
</html>
