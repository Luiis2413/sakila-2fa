<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $sql = "UPDATE actor SET first_name='$first_name', last_name='$last_name' WHERE actor_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM actor WHERE actor_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Actor</title>
 <!-- Bootstrap CSS para estilos -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Sakila App</a>
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
                        <a class="nav-link" href="../customers/index.php">Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../inventories/index.php">Inventories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../films/index.php">Films</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <h1>Edit Actor</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $row['actor_id'] ?>">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= $row['first_name'] ?>" required>
        <br>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= $row['last_name'] ?>" required>
        <br>
        <button type="submit">Save</button>
    </form>
</body>
</html>