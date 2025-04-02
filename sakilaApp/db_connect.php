<?php
$host = 'localhost';
$db   = 'sakila';
$user = 'root';
$pass = 'w2VPaPG£X,F\_35Y?#u9p[In@8.ky';
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>