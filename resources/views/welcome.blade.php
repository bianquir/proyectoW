<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /* General Tailwind CSS styles */
            *, ::after, ::before { box-sizing: border-box; }
            body { margin: 0; font-family: Figtree, sans-serif; background-color: #f3f4f6; }
            
            /* Custom styles for square container and inner elements */
            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #f3f4f6;
            }
            .square-box {
                width: 450px; /* Aumentado el tamaño del contenedor cuadrado */
                height: 450px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between; /* Distribuye espacio entre elementos */
                padding: 30px; /* Aumentado el padding para mayor espacio */
            }
            .title {
                font-size: 1.5rem;
                font-weight: 600;
                color: #111827;
                text-align: center;
                margin-top: 10px;
            }
            .button-group {
                display: flex;
                gap: 10px; /* Espacio entre los botones */
                margin-top: 20px; /* Ajustado para un mejor espaciado */
            }
            .button {
                padding: 12px 20px;
                font-size: 1rem;
                font-weight: 500;
                color: #ffffff;
                background-color: #409ab6;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
                flex: 1; /* Hace que los botones ocupen el mismo ancho */
                text-align: center;
            }
            .button:hover {
                background-color: #31758a;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="square-box">
                <div class="title">Elige tu acción</div>
                <picture>
                    <img src="{{asset('img/logo.webp')}}" alt="logo" style="width: 120px; height: 120px; border-radius: 8px;">
                </picture>
                <div class="button-group">
                    <a href="{{route('filament.admin.auth.login')}}">
                        <button class="button" style="width: 200px;">Iniciar Sesión</button>
                    </a>
                    <a href="{{route('register')}}">
                        <button class="button" style="width: 200px;">Registrarse</button>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>

