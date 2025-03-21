<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Afilidrop - Login/Register</title>

    <!-- Fonte futurista (Orbitron) -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

    <!-- Seu app.css + Bootstrap -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <style>
        /* Fundo com gradiente animado */
        html, body {
            height: 100%;
            margin: 0;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container flex para centralizar todo o conteúdo */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Card customizado para o formulário */
        .login-card {
            width: 100%;
            max-width: 500px;
            background: rgba(0, 0, 0, 0.6); /* fundo escuro semi-transparente */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            color: #fff; /* texto branco */
            padding: 30px;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Ajustes de formulário (labels, inputs, botões) */
        .login-card label {
            color: #fff; /* rótulos brancos */
        }
        .login-card .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
        }
        .login-card .form-control:focus {
            background-color: rgba(255, 255, 255, 0.3);
            color: #fff;
            box-shadow: none;
            outline: none;
        }
        .login-card .form-check-label {
            color: #fff;
        }
        .login-card .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            @yield('conteudo')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
