<?php
include '../db_connect.php';
include '../../auth_check.php'; // Asegurar autenticación

// Verificar el rol del usuario
$user_role = $_SESSION['user_role'] ?? ''; // Si no está definido, queda vacío (no tiene rol)

// Obtener el término de búsqueda (si existe)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Configuración de paginación
$rows_per_page = 10; // Número de filas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $rows_per_page; // Offset para la consulta SQL

// Consulta para obtener el total de películas (con búsqueda)
$total_sql = "SELECT COUNT(*) AS total 
              FROM film 
              WHERE title LIKE '%$search%' 
                OR release_year LIKE '%$search%' 
                OR rental_rate LIKE '%$search%' 
                OR length LIKE '%$search%' 
                OR rating LIKE '%$search%'";
$total_result = $mysqli->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];

// Calcular el total de páginas
$total_pages = ceil($total_rows / $rows_per_page);

// Consulta para obtener las películas con paginación y búsqueda
$sql = "SELECT film.film_id, film.title, film.release_year, film.rental_rate, film.length, film.rating 
        FROM film 
        WHERE title LIKE '%$search%' 
          OR release_year LIKE '%$search%' 
          OR rental_rate LIKE '%$search%' 
          OR length LIKE '%$search%' 
          OR rating LIKE '%$search%'
        LIMIT $offset, $rows_per_page";
$result = $mysqli->query($sql);

// Incluye el header
include '../header.php';
?>

<h1>Films</h1>

<!-- Formulario de búsqueda -->
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by title, release year, rental rate, length, or rating..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<!-- Botón para agregar nueva película (visible si no tiene rol o es diferente a usuario) -->
<?php if ($_SESSION['rol-acces'] == 'admin'): ?>
    <a href="create.php" class="btn btn-primary mb-3">Add New Film</a>
<?php endif; ?>

<!-- Tabla de películas -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Release Year</th>
            <th>Rental Rate</th>
            <th>Length</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['film_id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['release_year'] ?></td>
                <td><?= $row['rental_rate'] ?></td>
                <td><?= $row['length'] ?></td>
                <td><?= $row['rating'] ?></td>
                <td>
                    <?php if ($_SESSION['rol-acces'] == 'admin'): ?>
                        <a href="edit.php?id=<?= $row['film_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['film_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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
                <a class="page-link" href="index.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="index.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" aria-label="Next">
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