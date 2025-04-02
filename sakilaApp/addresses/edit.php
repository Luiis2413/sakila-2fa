<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $district = $_POST['district'];
    $city_id = $_POST['city_id'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];

    $sql = "UPDATE address 
            SET address='$address', address2='$address2', district='$district', 
                city_id=$city_id, postal_code='$postal_code', phone='$phone' 
            WHERE address_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM address WHERE address_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Obtener ciudades para el dropdown
$cities_sql = "SELECT city_id, city FROM city";
$cities_result = $mysqli->query($cities_sql);

// Incluye el header
include '../header.php';
?>

<h1>Edit Address</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['address_id'] ?>">
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?= $row['address'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="address2" class="form-label">Address 2</label>
        <input type="text" class="form-control" id="address2" name="address2" value="<?= $row['address2'] ?>">
    </div>
    <div class="mb-3">
        <label for="district" class="form-label">District</label>
        <input type="text" class="form-control" id="district" name="district" value="<?= $row['district'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="city_id" class="form-label">City</label>
        <select class="form-control" id="city_id" name="city_id" required>
            <?php while($city = $cities_result->fetch_assoc()): ?>
                <option value="<?= $city['city_id'] ?>" <?= $city['city_id'] == $row['city_id'] ? 'selected' : '' ?>>
                    <?= $city['city'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="postal_code" class="form-label">Postal Code</label>
        <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= $row['postal_code'] ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= $row['phone'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>