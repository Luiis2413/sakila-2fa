<?php
// Incluir el archivo de conexión a la base de datos
include '../db_connect.php';

// Incluir el header
include '../header.php';

// Configuración de la paginación
$results_per_page = 10; // Número de resultados por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $results_per_page; // Cálculo del offset

// Consulta SQL para obtener el total de registros
$sql_count = "SELECT COUNT(*) AS total FROM city";
$result_count = $mysqli->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $results_per_page); // Cálculo del total de páginas

// Consulta SQL para obtener city y country con paginación
$sql = "SELECT city.city, country.country 
        FROM city 
        JOIN country ON city.country_id = country.country_id
        LIMIT $offset, $results_per_page";
$result = $mysqli->query($sql);
?>

<!-- Contenido principal -->
<div class="container mt-5">
    <h1 class="mb-4">Cities and Countries</h1>
    <?php
    // Verificar si la consulta devolvió resultados
    if ($result && $result->num_rows > 0) {
        // Mostrar los datos en una tabla de Bootstrap
        echo '<div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>City</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>';
        // Recorrer cada fila de resultados
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["city"]) . "</td>
                    <td>" . htmlspecialchars($row["country"]) . "</td>
                  </tr>";
        }
        echo '</tbody>
              </table>
            </div>';

        // Mostrar controles de paginación
        echo '<nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">';
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Anterior</a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                    <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                  </li>';
        }
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Siguiente</a></li>';
        }
        echo '</ul>
              </nav>';
    } else {
        echo '<div class="alert alert-warning">No results found.</div>';
    }

    // Cerrar la conexión
    $mysqli->close();
    ?>
</div>

<!-- Bootstrap JS y dependencias -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>