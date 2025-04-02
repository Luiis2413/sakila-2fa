<?php
// setup-2fa.php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$ga = new PHPGangsta_GoogleAuthenticator();
$error = '';
$success = '';

// Generar un nuevo secreto si no existe
$userId = $_SESSION['user_id'];
$user = getUserById($userId);

if (empty($user['twofa_secret'])) {
    $secret = $ga->createSecret();
    
    // Guardar el secreto en la base de datos
    $stmt = $pdo->prepare("UPDATE users SET twofa_secret = ? WHERE id = ?");
    $stmt->execute([$secret, $userId]);
} else {
    $secret = $user['twofa_secret'];
}

$qrCodeUrl = $ga->getQRCodeGoogleUrl(APP_NAME . ' (' . $user['email'] . ')', $secret);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    
    if ($ga->verifyCode($secret, $code, 2)) {
        $success = 'Autenticación en dos pasos configurada correctamente!';
        $_SESSION['2fa_verified'] = true;
        header('Refresh: 2; URL=dashboard.php');
    } else {
        $error = 'Código incorrecto. Intenta nuevamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar 2FA - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body p-4 text-center">
                        <h2 class="mb-4">Configurar Autenticación en Dos Pasos</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php else: ?>
                            <p>Escanea este código QR con tu aplicación de autenticación (Google Authenticator, Authy, etc.)</p>
                            
                            <div class="mb-4">
                                <img src="<?= $qrCodeUrl ?>" alt="Código QR" class="img-fluid">
                            </div>
                            
                            <p>O introduce manualmente este código:</p>
                            <div class="alert alert-secondary mb-4">
                                <strong><?= chunk_split($secret, 4, ' ') ?></strong>
                            </div>
                            
                            <p>Introduce el código de 6 dígitos generado por tu aplicación para verificar:</p>
                            
                            <form method="POST" class="mt-3">
                                <div class="mb-3">
                                    <input type="text" class="form-control text-center" id="code" name="code" 
                                           placeholder="123456" required autofocus>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Verificar y Activar 2FA</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>