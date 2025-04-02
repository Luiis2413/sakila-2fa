<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $manager_staff_id = $_POST['manager_staff_id'];
    $address_id = $_POST['address_id'];

    $sql = "UPDATE store SET manager_staff_id=$manager_staff_id, address_id=$address_id WHERE store_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM store WHERE store_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Obtener gerentes y direcciones para los dropdowns
$managers_sql = "SELECT staff_id, first_name, last_name FROM staff";
$managers_result = $mysqli->query($managers_sql);

$addresses_sql = "SELECT address_id, address FROM address";
$addresses_result = $mysqli->query($addresses_sql);

// Incluye el header
include '../header.php';
?>

<h1>Edit Store</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['store_id'] ?>">
    <div class="mb-3">
        <label for="manager_staff_id" class="form-label">Manager</label>
        <select class="form-control" id="manager_staff_id" name="manager_staff_id" required>
            <?php while($manager = $managers_result->fetch_assoc()): ?>
                <option value="<?= $manager['staff_id'] ?>" <?= $manager['staff_id'] == $row['manager_staff_id'] ? 'selected' : '' ?>>
                    <?= $manager['first_name'] ?> <?= $manager['last_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="address_id" class="form-label">Address</label>
        <select class="form-control" id="address_id" name="address_id" required>
            <?php while($address = $addresses_result->fetch_assoc()): ?>
                <option value="<?= $address['address_id'] ?>" <?= $address['address_id'] == $row['address_id'] ? 'selected' : '' ?>>
                    <?= $address['address'] ?>
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