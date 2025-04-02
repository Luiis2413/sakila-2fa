<?php
include '../db_connect.php';

$error_message = ""; // Variable para almacenar el mensaje de error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $manager_staff_id = $_POST['manager_staff_id'];
    $address_id = $_POST['address_id'];

    // Verificar si el manager_staff_id ya está en uso
    $check_sql = "SELECT * FROM store WHERE manager_staff_id = $manager_staff_id";
    $check_result = $mysqli->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Si ya existe, establecer un mensaje de error
        $error_message = "Error: El gerente seleccionado ya está asignado a otra tienda.";
    } else {
        // Insertar la tienda si no existe
        $sql = "INSERT INTO store (manager_staff_id, address_id) VALUES ($manager_staff_id, $address_id)";
        if ($mysqli->query($sql)) {
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Error al crear la tienda: " . $mysqli->error;
        }
    }
}

// Obtener lista de gerentes (staff)
$staff_sql = "SELECT staff_id, first_name, last_name FROM staff";
$staff_result = $mysqli->query($staff_sql);

// Obtener lista de direcciones
$addresses_sql = "SELECT address_id, address FROM address";
$addresses_result = $mysqli->query($addresses_sql);

// Incluye el header
include '../header.php';
?>

<!-- Mostrar alerta de Bootstrap si hay un mensaje de error -->
<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<h1>Add New Store</h1>
<form method="POST">
    <div class="mb-3">
        <label for="manager_staff_id" class="form-label">Manager</label>
        <select class="form-control" id="manager_staff_id" name="manager_staff_id" required>
            <?php while($staff = $staff_result->fetch_assoc()): ?>
                <option value="<?= $staff['staff_id'] ?>"><?= $staff['first_name'] ?> <?= $staff['last_name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="address_id" class="form-label">Address</label>
        <select class="form-control" id="address_id" name="address_id" required>
            <?php while($address = $addresses_result->fetch_assoc()): ?>
                <option value="<?= $address['address_id'] ?>"><?= $address['address'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>