<?php
include '../db_connect.php';

// Obtener ciudades para el dropdown
$cities_sql = "SELECT city_id, city FROM city";
$cities_result = $mysqli->query($cities_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $district = $_POST['district'];
    $city_id = $_POST['city_id'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO address (address, address2, district, city_id, postal_code, phone) 
            VALUES ('$address', '$address2', '$district', $city_id, '$postal_code', '$phone')";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

// Incluye el header
include '../header.php';
?>

<h1>Add New Address</h1>
<form method="POST">
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
    </div>
    <div class="mb-3">
        <label for="address2" class="form-label">Address 2</label>
        <input type="text" class="form-control" id="address2" name="address2">
    </div>
    <div class="mb-3">
        <label for="district" class="form-label">District</label>
        <input type="text" class="form-control" id="district" name="district" required>
    </div>
    <div class="mb-3">
        <label for="city_id" class="form-label">City</label>
        <select class="form-control" id="city_id" name="city_id" required>
            <?php while($city = $cities_result->fetch_assoc()): ?>
                <option value="<?= $city['city_id'] ?>"><?= $city['city'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="postal_code" class="form-label">Postal Code</label>
        <input type="text" class="form-control" id="postal_code" name="postal_code">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>