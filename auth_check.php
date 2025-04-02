<?php
// auth_check.php

require_once __DIR__.'/includes/config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si el rol está definido (con valor predeterminado 'usuario')
if (!isset($_SESSION['rol'])) {
    require_once __DIR__.'/includes/db.php';
    
    try {
        $stmt = $pdo->prepare("SELECT rol FROM staff WHERE staff_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        $_SESSION['rol'] = $user['rol'] ?? 'usuario';
    } catch (PDOException $e) {
        die("Error al verificar el rol: " . $e->getMessage());
    }
}