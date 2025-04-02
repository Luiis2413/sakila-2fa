<?php
include '../db_connect.php';
include '../../auth_check.php'; // Asegurar autenticación

// Verificar el rol del usuario
$user_role = $_SESSION['user_role'] ?? ''; // Si no está definido, queda vacío (no tiene rol)

// Configuración de paginación
$rows_per_page = 10; // Número de filas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $rows_per_page; // Offset para la consulta SQL

// Consulta para obtener el total de tiendas
$total_sql = "SELECT COUNT(*) AS total FROM store";
$total_result = $mysqli->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];



// Calcular el total de páginas
$total_pages = ceil($total_rows / $rows_per_page);

// Consulta para obtener las tiendas con paginación
$sql = "SELECT store.store_id, staff.first_name, staff.last_name, address.address, city.city, country.country 
        FROM store 
        JOIN staff ON store.manager_staff_id = staff.staff_id 
        JOIN address ON store.address_id = address.address_id 
        JOIN city ON address.city_id = city.city_id 
        JOIN country ON city.country_id = country.country_id
        LIMIT $offset, $rows_per_page";
$result = $mysqli->query($sql);

// Incluye el header
include '../header.php';
?>

<h1>Stores</h1>

<!-- Botón para agregar nueva tienda (visible si no tiene rol o es diferente a usuario) -->
<?php if ($_SESSION['rol-acces'] == 'admin'): ?>
    <a href="create.php" class="btn btn-primary mb-3">Add New Store</a>
<?php endif; ?>

<!-- Tabla de tiendas -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Manager</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['store_id'] ?></td>
            <td><?= $row['first_name'] ?> <?= $row['last_name'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['city'] ?></td>
            <td><?= $row['country'] ?></td>
            <td>
                <?php if ($_SESSION['rol-acces'] == 'admin'): ?>
                    <a href="edit.php?id=<?= $row['store_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $row['store_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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