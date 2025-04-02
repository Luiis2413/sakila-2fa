<?php
// verify-2fa.php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$ga = new PHPGangsta_GoogleAuthenticator();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    
    // Obtener el secreto del usuario
    $userId = $_SESSION['user_id'];
    $user = getUserById($userId);
    
    if ($ga->verifyCode($user['twofa_secret'], $code, 2)) {
        $_SESSION['2fa_verified'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Código 2FA incorrecto';
    }
}

// Obtener el secreto del usuario para mostrar el QR (si es la primera vez)
$user = getUserById($_SESSION['user_id']);
$secret = $user['twofa_secret'];
$qrCodeUrl = $ga->getQRCodeGoogleUrl(APP_NAME, $secret);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar 2FA - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4 text-center">
                        <h2 class="mb-4">Autenticación en dos pasos</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <p>Introduce el código de tu aplicación de autenticación</p>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control text-center" id="code" name="code" 
                                       placeholder="123456" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Verificar</button>
                        </form>
                        
                        <hr>
                        
                        <div class="mt-3">
                            <p>¿No tienes configurado 2FA?</p>
                            <a href="setup-2fa.php" class="btn btn-outline-secondary">Configurar 2FA</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>