<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $sql = "UPDATE category SET name='$name' WHERE category_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM category WHERE category_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Incluye el header
include '../header.php';
?>

<h1>Edit Category</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['category_id'] ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>