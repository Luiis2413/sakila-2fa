<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !isUserAdmin($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'], $_POST['new_role'])) {
    $staffId = $_POST['staff_id'];
    $newRole = $_POST['new_role'];
    
    if (updateUserRole($staffId, $newRole)) {
        $success = 'Rol actualizado correctamente';
    } else {
        $error = 'Error al actualizar el rol';
    }
}

$stmt = $pdo->query("SELECT staff_id, username, first_name, last_name, rol FROM staff ORDER BY last_name, first_name");
$staffMembers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Roles - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Administrar Roles de Usuario</h2>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Usuario</label>
                                <select name="staff_id" class="form-select" required>
                                    <?php foreach ($staffMembers as $member): ?>
                                        <option value="<?= $member['staff_id'] ?>">
                                            <?= htmlspecialchars($member['last_name'] . ', ' . $member['first_name'] . ' (' . $member['username'] . ') - Actual: ' . $member['rol']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nuevo Rol</label>
                                <select name="new_role" class="form-select" required>
                                    <option value="usuario">Usuario</option>
                                    <option value="admin">Administrador</option>
                                    <option value="empleado">Empleado</option>

                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Actualizar Rol</button>
                        </form>
                        
                        <div class="mt-4">
                            <h4>Descripción de Roles</h4>
                            <ul>
                                <li><strong>Administrador:</strong> Acceso completo al sistema</li>
                             
                                <li><strong>Usuario:</strong> Acceso básico</li>

                      
                            </ul>
                          
                        <a class="nav-link" href="./dashboard.php"><---Dashboard</a>
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>