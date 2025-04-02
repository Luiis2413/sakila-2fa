<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['2fa_verified'])) {
    header('Location: login.php');
    exit;
}

$user = getUserWithRole($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .staff-info {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .action-buttons .btn {
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?> (<?= $user['rol'] ?>)</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Bienvenido, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                        <p class="card-text">Sistema de gestión Sakila con autenticación en dos factores.</p>
                        
                        <div class="staff-info mt-4">
                            <h4>Información del Staff</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>ID de Usuario:</strong> <?= $user['staff_id'] ?></p>
                                    <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($user['username']) ?></p>
                                    <p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'No especificado') ?></p>
                                    <p><strong>Estado:</strong> <?= $user['active'] ? 'Activo' : 'Inactivo' ?></p>
                                    <p><strong>Última actualización:</strong> <?= $user['last_update'] ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Estado de 2FA</h4>
                            <?php if (is2FAEnabled($user['staff_id'])): ?>
                                <div class="alert alert-success">
                                    Autenticación en dos pasos ACTIVADA
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    Autenticación en dos pasos NO ACTIVADA
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <?php if (is2FAEnabled($user['staff_id'])): ?>
                                <a href="setup-2fa.php" class="btn btn-outline-primary">
                                    Reconfigurar 2FA
                                </a>
                            <?php else: ?>
                                <a href="setup-2fa.php" class="btn btn-primary">
                                    Activar 2FA
                                </a>
                            <?php endif; ?>
                            
                            <a href="./sakilaApp" class="btn btn-success">
                                Acceder a Sakila App
                            </a>
                            
                            <?php if (isUserAdmin($_SESSION['user_id'])): ?>
                                <a href="manage_roles.php" class="btn btn-warning">
                                    Administrar Roles
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $_SESSION['rol-acces'] = $user['rol'];
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>