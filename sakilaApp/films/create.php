<?php
include '../db_connect.php';

// Obtener idiomas para el dropdown
$languages_sql = "SELECT language_id, name FROM language";
$languages_result = $mysqli->query($languages_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_year = $_POST['release_year'];
    $language_id = $_POST['language_id'];
    $rental_duration = $_POST['rental_duration'];
    $rental_rate = $_POST['rental_rate'];
    $length = $_POST['length'];
    $replacement_cost = $_POST['replacement_cost'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO film (title, description, release_year, language_id, rental_duration, rental_rate, length, replacement_cost, rating) 
            VALUES ('$title', '$description', $release_year, $language_id, $rental_duration, $rental_rate, $length, $replacement_cost, '$rating')";
    $mysqli->query($sql);

    header("Location: index.php");
    exit;
}

// Incluye el header
include '../header.php';
?>

<h1>Add New Film</h1>
<form method="POST">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <div class="mb-3">
        <label for="release_year" class="form-label">Release Year</label>
        <input type="number" class="form-control" id="release_year" name="release_year">
    </div>
    <div class="mb-3">
        <label for="language_id" class="form-label">Language</label>
        <select class="form-control" id="language_id" name="language_id" required>
            <?php while($language = $languages_result->fetch_assoc()): ?>
                <option value="<?= $language['language_id'] ?>"><?= $language['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="rental_duration" class="form-label">Rental Duration</label>
        <input type="number" class="form-control" id="rental_duration" name="rental_duration" required>
    </div>
    <div class="mb-3">
        <label for="rental_rate" class="form-label">Rental Rate</label>
        <input type="number" step="0.01" class="form-control" id="rental_rate" name="rental_rate" required>
    </div>
    <div class="mb-3">
        <label for="length" class="form-label">Length</label>
        <input type="number" class="form-control" id="length" name="length">
    </div>
    <div class="mb-3">
        <label for="replacement_cost" class="form-label">Replacement Cost</label>
        <input type="number" step="0.01" class="form-control" id="replacement_cost" name="replacement_cost" required>
    </div>
    <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <select class="form-control" id="rating" name="rating">
            <option value="G">G</option>
            <option value="PG">PG</option>
            <option value="PG-13">PG-13</option>
            <option value="R">R</option>
            <option value="NC-17">NC-17</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
// Incluye el footer
include '../footer.php';
?>