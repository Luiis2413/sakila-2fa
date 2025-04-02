<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address_id = $_POST['address_id'];
    $store_id = $_POST['store_id']; // Nuevo campo para store_id
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $sql = "UPDATE staff 
            SET first_name='$first_name', last_name='$last_name', email='$email', 
                address_id=$address_id, store_id=$store_id, username='$username' 
                " . ($password ? ", password='$password'" : "") . "
            WHERE staff_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM staff WHERE staff_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Obtener direcciones y tiendas para los dropdowns
$addresses_sql = "SELECT address_id, address FROM address";
$addresses_result = $mysqli->query($addresses_sql);

$stores_sql = "SELECT store_id FROM store";
$stores_result = $mysqli->query($stores_sql);

// Incluye el header
include '../header.php';
?>

<h1>Edit Manager</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['staff_id'] ?>">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $row['first_name'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $row['last_name'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $row['email'] ?>">
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
    <div class="mb-3">
        <label for="store_id" class="form-label">Store</label>
        <select class="form-control" id="store_id" name="store_id" required>
            <?php while($store = $stores_result->fetch_assoc()): ?>
                <option value="<?= $store['store_id'] ?>" <?= $store['store_id'] == $row['store_id'] ? 'selected' : '' ?>>
                    Store <?= $store['store_id'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= $row['username'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password (leave blank to keep current)</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>