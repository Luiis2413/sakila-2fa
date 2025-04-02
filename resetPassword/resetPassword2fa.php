<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'PHPGangsta/GoogleAuthenticator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $code = $_POST['code'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    $user = getUserByUsername($username);
    
    if (!$user) {
        $error = 'El usuario no existe';
    } else {
        $ga = new PHPGangsta_GoogleAuthenticator();
        if ($ga->verifyCode($user['twofa_secret'], $code, 2)) {
            $_SESSION['user_id'] = $user['staff_id'];
            $_SESSION['username'] = $username;
            
            if (!empty($newPassword) && !empty($confirmPassword)) {
                if ($newPassword === $confirmPassword) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE staff SET password = ? WHERE staff_id = ?");
                    $stmt->execute([$hashedPassword, $user['staff_id']]);
                    
                    $success = 'Contraseña actualizada correctamente. Puedes iniciar sesión con tu nueva contraseña.';
                } else {
                    $error = 'Las contraseñas no coinciden';
                }
            }
        } else {
            $error = 'Código 2FA incorrecto';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4 text-center">
                        <h2 class="mb-4">Restablecer Contraseña</h2>
                        
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"> <?= $error ?> </div>
                        <?php elseif (!empty($success)): ?>
                            <div class="alert alert-success"> <?= $success ?> </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de Google Authenticator</label>
                                <input type="text" class="form-control text-center" id="code" name="code" placeholder="123456" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
