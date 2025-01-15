@extends('layouts.app')
@section('title', "Aguardando..")
@section('conteudo')
<style>
        body {
            background: radial-gradient(circle, #1b1b2f, #162447);
            color: #fff;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }

        .neon-text {
            font-size: 2.5rem;
            text-shadow: 0 0 5px #0ff, 0 0 10px #0ff, 0 0 20px #0ff, 0 0 40px #0ff;
            animation: neon-flicker 1.5s infinite alternate;
        }

        @keyframes neon-flicker {
            0%, 100% {
                text-shadow: 0 0 5px #0ff, 0 0 10px #0ff, 0 0 20px #0ff, 0 0 40px #0ff;
            }
            50% {
                text-shadow: 0 0 2px #0ff, 0 0 8px #0ff, 0 0 16px #0ff, 0 0 32px #0ff;
            }
        }

        .glow-circle {
            position: absolute;
            top: 55%;
            left: 55%;
            width: 300px;
            height: 300px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), rgba(0,255,255,0.05));
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.8;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.4;
            }
        }

        .message {
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .message p {
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .button-container {
            margin-top: 2rem;
        }

        .btn-cta {
            color: #0ff;
            border: 1px solid #0ff;
            background: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            background: #0ff;
            color: #000;
            text-decoration: none;
            box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
        }
    </style>

    <div class="container py-4 mt-4">
        <div class="glow-circle"></div>
        <div class="message">
            <h1 class="neon-text">Página Disponível em Breve</h1>
            <p>Estamos trabalhando em algo incrível! Fique ligado para novidades.</p>
            <div class="button-container">
                <a href="{{ url('/home') }}" class="btn btn-cta">Voltar ao Início</a>
            </div>
        </div>
    </div>
@endsection


