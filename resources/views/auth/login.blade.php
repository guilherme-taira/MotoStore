    @extends('layouts.login')
    @section('conteudo')

    <style>
        /* Fundo interativo */
        .rocket {
            position: relative;
            background: white;
            width: 100px;
            height: 200px;

            clip-path: polygon(50% 0, 80% 20%, 80% 70%, 90% 80%, 70% 100%, 30% 100%, 10% 80%, 20% 70%, 20% 20%);
            margin: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .rocket::before {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 50px;
            background: linear-gradient(to bottom, orange, red);
            clip-path: polygon(50% 0, 80% 50%, 50% 100%, 20% 50%);
        }
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(120deg, #0c1b2d, #001f40);
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .background-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .triangle {
            position: absolute;
            background: #ff8c00;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }
        .triangle.blue {
            background: #00caff;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }
        .triangle-1 {
            width: 200px;
            height: 200px;

            top: 10%;
            left: 5%;
        }
        .triangle-2 {
            width: 300px;
            height: 300px;
            top: 50%;

        }

        /* Estilo do card */
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
        }
        .login-card h4 {
            font-weight: bold;
            color: #003366;
        }
        .btn-primary {
            background-color: #00caff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0077b3;
        }
        .input-group-text {
            background-color: #00caff;
            color: white;
        }
        .login-logo {
            width: 120px;
            margin-bottom: 20px;
        }
    </style>

    <!-- Fundo Interativo -->
    <div class="background-elements">
        <div class="triangle triangle-1"></div>
        <div class="triangle triangle-2 blue"></div>
    </div>

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-lg" style="width: 400px;">
            <div class="card-header text-center bg-primary text-white">
                <h4>Afilidrop</h4>
            </div>
            <div class="card-body">
                @if (session('mgs_login'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{ session('mgs_login') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>

                        {{-- @if (Route::has('password.request'))
                            <a class="btn btn-link text-primary" href="{{ route('password.request') }}">
                                {{ __('Esqueci a Senha') }}
                            </a>
                        @endif --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
