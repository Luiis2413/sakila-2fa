<?php
include '../db_connect.php';
include '../../auth_check.php'; // Asegurar autenticación

// Verificar el rol del usuario
$user_role = $_SESSION['user_role'] ?? ''; // Si no está definido, queda vacío (no tiene rol)

// Configuración de paginación
$rows_per_page = 10; // Número de filas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $rows_per_page; // Offset para la consulta SQL

// Consulta para obtener el total de gerentes
$total_sql = "SELECT COUNT(*) AS total FROM staff";
$total_result = $mysqli->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];

// Calcular el total de páginas
$total_pages = ceil($total_rows / $rows_per_page);

// Consulta para obtener los gerentes con paginación
$sql = "SELECT staff.staff_id, staff.first_name, staff.last_name, staff.email, staff.username, address.address, city.city, country.country 
        FROM staff 
        JOIN address ON staff.address_id = address.address_id 
        JOIN city ON address.city_id = city.city_id 
        JOIN country ON city.country_id = country.country_id
        LIMIT $offset, $rows_per_page";
$result = $mysqli->query($sql);

// Incluye el header
include '../header.php';
?>

<h1>Managers</h1>

<!-- Botón para agregar nuevo gerente (visible si no tiene rol o es diferente a usuario) -->
<?php if ($_SESSION['rol-acces'] == 'admin'): ?>
    <a href="create.php" class="btn btn-primary mb-3">Add New Manager</a>
<?php endif; ?>

<!-- Tabla de gerentes -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['staff_id'] ?></td>
            <td><?= $row['first_name'] ?></td>
            <td><?= $row['last_name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['city'] ?></td>
            <td><?= $row['country'] ?></td>
            <td>
                <?php if ($_SESSION['rol-acces'] == 'admin'): ?>
                    <a href="edit.php?id=<?= $row['staff_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $row['staff_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                <?php else: ?>
                    <span class="text-muted">Actions disabled</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Paginación -->
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="index.php?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php
// Incluye el footer
include '../footer.php';
?>