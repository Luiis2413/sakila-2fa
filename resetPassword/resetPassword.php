<?php
// check_user.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'w2VPaPG£X,F\_35Y?#u9p[In@8.ky');
define('DB_NAME', 'sakila');

// Conexión a la base de datos
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Función para verificar el código de Google Authenticator
function verifyGoogleAuthCode($secret, $code) {
    require_once '../libs/PHPGangsta/GoogleAuthenticator.php';
    $ga = new PHPGangsta_GoogleAuthenticator();
    return $ga->verifyCode($secret, $code, 2); // 2 = Tolerancia de 2 minutos
}

$message = '';
$username = '';
$code = '';
$secret = '';  // Guardaremos el secret para verificar el código
$newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';  // Verificar si el campo existe
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $code = trim($_POST['code']);
    
    if (empty($username)) {
        $message = "Por favor ingresa un nombre de usuario";
    } elseif (empty($code)) {
        $message = "Por favor ingresa el código de Google Authenticator";
    } elseif (empty($newPassword)) {
        $message = "Por favor ingresa la nueva contraseña";
    } else {
        try {
            // Verificar si el usuario existe
            $stmt = $conn->prepare("SELECT staff_id, username, twofa_secret FROM staff WHERE username = ? LIMIT 1");
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Verificar el código 2FA
                $secret = $user['twofa_secret'];
                if (verifyGoogleAuthCode($secret, $code)) {
                    // Actualizar la contraseña si la verificación 2FA es correcta
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateStmt = $conn->prepare("UPDATE staff SET password = ? WHERE staff_id = ?");
                    $updateStmt->bindParam(1, $hashedPassword);
                    $updateStmt->bindParam(2, $user['staff_id']);
                    $updateStmt->execute();
                    
                    $successMessage = "Contraseña actualizada correctamente.";
                    header("Refresh: 2; ../login.php");

                } else {
                    $message = "El código 2FA es incorrecto.";
                }
            } else {
                $message = "El usuario '$username' no existe.";
            }
        } catch (Exception $e) {
            $message = "Error al verificar el usuario o código: " . $e->getMessage();
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
    <style>
        .card {
            margin-top: 5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Restablecer Contraseña</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($username) ?>" required
                                       placeholder="Ingresa el nombre de usuario">
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de Google Authenticator</label>
                                <input type="text" class="form-control text-center" id="code" name="code" 
                                       value="<?= htmlspecialchars($code) ?>" required placeholder="Ingresa el código">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" 
                                       value="<?= htmlspecialchars($newPassword) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                        </form>
                        
                        <?php if ($message): ?>
                            <div class="alert alert-danger mt-3">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($successMessage): ?>
                            <div class="alert alert-success mt-3">
                                <?= htmlspecialchars($successMessage) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
