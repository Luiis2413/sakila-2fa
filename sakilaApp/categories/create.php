<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $sql = "INSERT INTO category (name) VALUES ('$name')";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

// Incluye el header
include '../header.php';
?>

<h1>Add New Category</h1>
<form method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>