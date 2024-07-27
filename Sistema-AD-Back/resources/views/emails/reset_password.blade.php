<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            color: #fff;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Restablecer Contraseña</h1>

        <!-- Mostrar mensajes de error si existen -->
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario para restablecer la contraseña -->
        <form action="{{ url('/restablecer-contrasena') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label for="contrasena">Nueva Contraseña:</label>
            <input type="password" name="contrasena" required>

            <label for="contrasena_confirmation">Confirmar Contraseña:</label>
            <input type="password" name="contrasena_confirmation" required>

            <button type="submit">Restablecer Contraseña</button>
        </form>
    </div>
</body>
</html>
