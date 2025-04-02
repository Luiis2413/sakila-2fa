<?php
// includes/config.php
session_start();

// Configuración de la base de datos
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'w2VPaPG£X,F\_35Y?#u9p[In@8.ky');
    define('DB_NAME', 'sakila');
}

// Configuración de la aplicación
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Sistema de Login con 2FA');
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', true);
}

// Incluir el autenticador Google Authenticator
require_once __DIR__.'/../libs/PHPGangsta/GoogleAuthenticator.php';
