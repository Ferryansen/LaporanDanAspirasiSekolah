<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgent Sirine</title>

    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .button{
            background-color: #BB2D3B;
            color: white;
            border: none;
            padding: 30px 20px;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            font-size: 50px;
        }
    </style>
    
</head>
<body>
    <button id="toggle-sound" class="button">
        <i class="fa-solid fa-volume-high"></i>
    </button>

    <audio id="siren-sound" loop>
        <source src="{{ asset('web_assets/sounds/sirine.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script src="https://kit.fontawesome.com/f98710255c.js" crossorigin="anonymous"></script>    <!-- JavaScript for audio control -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('toggle-sound');
            const audio = document.getElementById('siren-sound');

            button.addEventListener('click', function() {
                if (audio.paused) {
                    audio.play();
                    button.innerHTML = '<i class="fa-solid fa-stop"></i>';
                } else {
                    audio.pause();
                    button.innerHTML = '<i class="fa-solid fa-volume-high"></i>';
                }
            });
        });
    </script>
</body>
</html>
