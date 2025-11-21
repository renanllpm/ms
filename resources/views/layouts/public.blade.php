<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega-Sena - Faça sua Aposta</title>
    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .megasena-gradient {
            background: linear-gradient(135deg, #00983F 0%, #00A94F 50%, #38C172 100%);
        }

        .megasena-ball {
            background: linear-gradient(145deg, #00A94F, #00983F);
            box-shadow: 0 4px 15px rgba(0, 152, 63, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .megasena-ball:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 152, 63, 0.4);
        }

        .megasena-ball.selected {
            background: linear-gradient(145deg, #FFD700, #FFA500);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.5);
            transform: scale(1.1);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .success-check {
            animation: checkmark 0.6s ease;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <x-dialog />
    <x-toast />

    {{ $slot }}

    @livewireScripts
</body>

</html>
