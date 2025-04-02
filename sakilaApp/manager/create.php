<?php
include '../db_connect.php';

// Obtener direcciones para el dropdown
$addresses_sql = "SELECT address_id, address FROM address";
$addresses_result = $mysqli->query($addresses_sql);

// Obtener tiendas disponibles para el dropdown
$stores_sql = "SELECT store_id FROM store";
$stores_result = $mysqli->query($stores_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address_id = $_POST['address_id'];
    $store_id = $_POST['store_id']; // Nuevo campo para store_id
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Insertar el gerente (staff) con el store_id
    $sql = "INSERT INTO staff (first_name, last_name, email, address_id, store_id, username, password) 
            VALUES ('$first_name', '$last_name', '$email', $address_id, $store_id, '$username', '$password')";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

// Incluye el header
include '../header.php';
?>

<h1>Add New Manager</h1>
<form method="POST">
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
    <div class="mb-3">
        <label for="store_id" class="form-label">Store</label>
        <select class="form-control" id="store_id" name="store_id" required>
            <?php while($store = $stores_result->fetch_assoc()): ?>
                <option value="<?= $store['store_id'] ?>">Store <?= $store['store_id'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>