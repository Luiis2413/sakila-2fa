<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $store_id = 1; // Asignar a la tienda 1 por defecto

    // Validaciones
    if (empty($username)) {
        $errors['username'] = 'El nombre de usuario es requerido';
    } elseif (strlen($username) < 4) {
        $errors['username'] = 'El nombre de usuario debe tener al menos 4 caracteres';
    } elseif (getUserByUsername($username)) {
        $errors['username'] = 'Este nombre de usuario ya está registrado';
    }

    if (empty($first_name)) {
        $errors['first_name'] = 'El nombre es requerido';
    }

    if (empty($last_name)) {
        $errors['last_name'] = 'El apellido es requerido';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El email no es válido';
    }

    if (empty($password)) {
        $errors['password'] = 'La contraseña es requerida';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Las contraseñas no coinciden';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $pdo->prepare("SELECT address_id FROM address LIMIT 1");
            $stmt->execute();
            $address = $stmt->fetch();
            $address_id = $address['address_id'] ?? 1;

            $stmt = $pdo->prepare("INSERT INTO staff 
                                  (first_name, last_name, email, username, password, 
                                   store_id, address_id, active, rol) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, 'usuario')");
            $stmt->execute([
                $first_name,
                $last_name,
                $email,
                $username,
                $hashed_password,
                $store_id,
                $address_id
            ]);
            
            $success = 'Registro exitoso. Ahora puedes <a href="login.php">iniciar sesión</a>.';
        } catch (PDOException $e) {
            $errors['general'] = 'Error al registrar el usuario: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error { color: #dc3545; font-size: 0.875em; }
        .form-container { max-width: 600px; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 form-container">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Registro de Nuevo Staff</h2>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php else: ?>
                        
                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger"><?= $errors['general'] ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" 
                                           id="first_name" name="first_name" value="<?= htmlspecialchars($first_name ?? '') ?>" required>
                                    <?php if (isset($errors['first_name'])): ?>
                                        <div class="error"><?= $errors['first_name'] ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Apellido</label>
                                    <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" 
                                           id="last_name" name="last_name" value="<?= htmlspecialchars($last_name ?? '') ?>" required>
                                    <?php if (isset($errors['last_name'])): ?>
                                        <div class="error"><?= $errors['last_name'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                       id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" required>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="error"><?= $errors['username'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email (opcional)</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="error"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           id="password" name="password" required>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="error"><?= $errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                           id="confirm_password" name="confirm_password" required>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="error"><?= $errors['confirm_password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
                        </div>
                        
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>