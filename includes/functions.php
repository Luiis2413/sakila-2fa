<?php
// includes/functions.php
require_once 'db.php';

// includes/functions.php

// Obtener usuario por username (en lugar de email)
function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

// Obtener usuario por ID
function getUserById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Verificar contraseña (compatible con el hash antiguo SHA1 y nuevo password_hash)
function verifyPassword($password, $hash) {
    // Si el hash es SHA1 (formato antiguo de Sakila)
    if (strlen($hash) == 40 && ctype_xdigit($hash)) {
        return sha1($password) === $hash;
    }
    // Si es un hash moderno (password_hash)
    return password_verify($password, $hash);
}

// Verificar si 2FA está habilitado
function is2FAEnabled($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT twofa_enabled FROM staff WHERE staff_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    return !empty($user['twofa_enabled']);
}


// Agrega estas funciones al final de tu functions.php

function getUserWithRole($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT *, rol FROM staff WHERE staff_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function updateUserRole($userId, $newRole) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE staff SET rol = ? WHERE staff_id = ?");
    return $stmt->execute([$newRole, $userId]);
}

function isUserAdmin($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM staff WHERE staff_id = ? AND rol = 'admin'");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() > 0;
}

function checkUserRole($userId, $requiredRole) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM staff WHERE staff_id = ? AND rol = ?");
    $stmt->execute([$userId, $requiredRole]);
    return $stmt->fetchColumn() > 0;
}
//-------------
//function getUserByEmail($email) {
  //  global $pdo;
   // $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    //$stmt->execute([$email]);
   // return $stmt->fetch();
//}

//function verifyPassword($password, $hash) {
  //  return password_verify($password, $hash);
//}

//function is2FAEnabled($userId) {
 //   global $pdo;
 //   $stmt = $pdo->prepare("SELECT twofa_secret FROM users WHERE id = ?");
 //   $stmt->execute([$userId]);
 //   $user = $stmt->fetch();
  //  return !empty($user['twofa_secret']);
//}
//function getUserById($userId) {
 //   global $pdo;
  //  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
   // $stmt->execute([$userId]);
   // return $stmt->fetch();
//}
