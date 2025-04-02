<?php
include '../db_connect.php';

// Obtener direcciones para el dropdown
$addresses_sql = "SELECT address_id, address FROM address";
$addresses_result = $mysqli->query($addresses_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address_id = $_POST['address_id'];
    $active = isset($_POST['active']) ? 1 : 0;

    $sql = "INSERT INTO customer (store_id, first_name, last_name, email, address_id, active, create_date) 
            VALUES ($store_id, '$first_name', '$last_name', '$email', $address_id, $active, NOW())";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

// Incluye el header
include '../header.php';
?>

<h1>Add New Customer</h1>
<form method="POST">
    <div class="mb-3">
        <label for="store_id" class="form-label">Store ID</label>
        <input type="number" class="form-control" id="store_id" name="store_id" required>
    </div>
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="address_id" class="form-label">Address</label>
        <select class="form-control" id="address_id" name="address_id" required>
            <?php while($address = $addresses_result->fetch_assoc()): ?>
                <option value="<?= $address['address_id'] ?>"><?= $address['address'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="active" name="active" checked>
        <label class="form-check-label" for="active">Active</label>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>