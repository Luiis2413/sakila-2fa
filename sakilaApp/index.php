<?php
// Redireccionar después de 5 segundos
header("Refresh: 2; ./actors");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redireccionando...</title>
    <style>
        /* Estilos para la animación de carga */
        .loader {
            border: 16px solid #f3f3f3; /* Fondo del círculo */
            border-top: 16px solid #3498db; /* Color del círculo */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite; /* Animación */
            margin: 50px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="loader"></div>
</body>
</html>