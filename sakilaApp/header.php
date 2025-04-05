<?php
// header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakila App</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* Ajuste para el navbar fijo */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Sakila App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../actors/index.php">Actors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../addresses/index.php">Addresses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../categories/index.php">Categories</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="../films/index.php">Films</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../store/index.php">Store</a>
                    </li>
                    <?php 
if ($_SESSION['rol-acces'] != 'usuario') {
    echo '
    <li class="nav-item">
    <a class="nav-link" href="../customers/index.php">Customers</a>
     </li>
     <li class="nav-item">
        <a class="nav-link" href="../inventories/index.php">Inventories</a>
    </li>    
   ';
}

if ($_SESSION['rol-acces'] == 'admin') {
    echo '
    <li class="nav-item">
        <a class="nav-link" href="../manager/index.php">Manager</a>
    </li>
   ';
}
?>
 </li>
    <li class="nav-item">
        <a class="nav-link" href="../../dashboard.php">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../../logout.php">Cerrar sesión</a>
    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">