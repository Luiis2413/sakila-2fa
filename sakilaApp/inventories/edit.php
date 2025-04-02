<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $film_id = $_POST['film_id'];
    $store_id = $_POST['store_id'];

    $sql = "UPDATE inventory SET film_id=$film_id, store_id=$store_id WHERE inventory_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM inventory WHERE inventory_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Obtener películas y tiendas para los dropdowns
$films_sql = "SELECT film_id, title FROM film";
$films_result = $mysqli->query($films_sql);

$stores_sql = "SELECT store_id FROM store";
$stores_result = $mysqli->query($stores_sql);

// Incluye el header
include '../header.php';
?>

<h1>Edit Inventory</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['inventory_id'] ?>">
    <div class="mb-3">
        <label for="film_id" class="form-label">Film</label>
        <select class="form-control" id="film_id" name="film_id" required>
            <?php while($film = $films_result->fetch_assoc()): ?>
                <option value="<?= $film['film_id'] ?>" <?= $film['film_id'] == $row['film_id'] ? 'selected' : '' ?>>
                    <?= $film['title'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="store_id" class="form-label">Store ID</label>
        <select class="form-control" id="store_id" name="store_id" required>
            <?php while($store = $stores_result->fetch_assoc()): ?>
                <option value="<?= $store['store_id'] ?>" <?= $store['store_id'] == $row['store_id'] ? 'selected' : '' ?>>
                    <?= $store['store_id'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>