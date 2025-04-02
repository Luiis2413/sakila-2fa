<?php
include '../db_connect.php';
include '../../auth_check.php'; // Asegúrate de incluir tu archivo de verificación de autenticación

// Verificar el rol del usuario
$user_role = $_SESSION['user_role'] ?? ''; // Asume que el rol está almacenado en la sesión, dejamos vacío si no tiene rol

// Obtener el término de búsqueda (si existe)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Configurar la paginación
$limit = 10; // Número de actores por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Cálculo del offset

// obtener rol de usuario
//print($_SESSION['rol-acces']);

// Consulta para obtener los actores (con búsqueda y paginación)
$sql = "SELECT * FROM actor 
        WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' 
        LIMIT $limit OFFSET $offset";
$result = $mysqli->query($sql);

// Consulta para contar el total de actores (para la paginación)
$count_sql = "SELECT COUNT(*) AS total FROM actor 
              WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
$count_result = $mysqli->query($count_sql);
$total_actors = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_actors / $limit); // Cálculo del total de páginas

// Incluye el header
include '../header.php';
?>

<h1>Actors</h1>

<!-- Formulario de búsqueda -->
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search actors by name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<!-- Botón para agregar nuevo actor (visible si no tiene rol o es diferente a usuario) -->
<?php if ($_SESSION['rol-acces'] == 'admin'): ?>
    <a href="create.php" class="btn btn-primary mb-3">Add New Actor</a>
<?php endif; ?>

<!-- Tabla de actores -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['actor_id'] ?></td>
                <td><?= $row['first_name'] ?></td>
                <td><?= $row['last_name'] ?></td>
                <td>
                    <?php if ($_SESSION['rol-acces'] == 'admin'): ?>
                        <a href="edit.php?id=<?= $row['actor_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['actor_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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