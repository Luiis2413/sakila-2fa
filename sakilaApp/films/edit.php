<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_year = $_POST['release_year'];
    $language_id = $_POST['language_id'];
    $rental_duration = $_POST['rental_duration'];
    $rental_rate = $_POST['rental_rate'];
    $length = $_POST['length'];
    $replacement_cost = $_POST['replacement_cost'];
    $rating = $_POST['rating'];

    $sql = "UPDATE film 
            SET title='$title', description='$description', release_year=$release_year, 
                language_id=$language_id, rental_duration=$rental_duration, rental_rate=$rental_rate, 
                length=$length, replacement_cost=$replacement_cost, rating='$rating' 
            WHERE film_id=$id";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM film WHERE film_id=$id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Obtener idiomas para el dropdown
$languages_sql = "SELECT language_id, name FROM language";
$languages_result = $mysqli->query($languages_sql);

// Incluye el header
include '../header.php';
?>

<h1>Edit Film</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $row['film_id'] ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= $row['title'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"><?= $row['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="release_year" class="form-label">Release Year</label>
        <input type="number" class="form-control" id="release_year" name="release_year" value="<?= $row['release_year'] ?>">
    </div>
    <div class="mb-3">
        <label for="language_id" class="form-label">Language</label>
        <select class="form-control" id="language_id" name="language_id" required>
            <?php while($language = $languages_result->fetch_assoc()): ?>
                <option value="<?= $language['language_id'] ?>" <?= $language['language_id'] == $row['language_id'] ? 'selected' : '' ?>>
                    <?= $language['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="rental_duration" class="form-label">Rental Duration</label>
        <input type="number" class="form-control" id="rental_duration" name="rental_duration" value="<?= $row['rental_duration'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="rental_rate" class="form-label">Rental Rate</label>
        <input type="number" step="0.01" class="form-control" id="rental_rate" name="rental_rate" value="<?= $row['rental_rate'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="length" class="form-label">Length</label>
        <input type="number" class="form-control" id="length" name="length" value="<?= $row['length'] ?>">
    </div>
    <div class="mb-3">
        <label for="replacement_cost" class="form-label">Replacement Cost</label>
        <input type="number" step="0.01" class="form-control" id="replacement_cost" name="replacement_cost" value="<?= $row['replacement_cost'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <select class="form-control" id="rating" name="rating">
            <option value="G" <?= $row['rating'] == 'G' ? 'selected' : '' ?>>G</option>
            <option value="PG" <?= $row['rating'] == 'PG' ? 'selected' : '' ?>>PG</option>
            <option value="PG-13" <?= $row['rating'] == 'PG-13' ? 'selected' : '' ?>>PG-13</option>
            <option value="R" <?= $row['rating'] == 'R' ? 'selected' : '' ?>>R</option>
            <option value="NC-17" <?= $row['rating'] == 'NC-17' ? 'selected' : '' ?>>NC-17</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>