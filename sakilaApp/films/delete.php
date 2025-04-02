<?php
include '../db_connect.php';

$id = $_GET['id'];
$sql = "DELETE FROM film WHERE film_id=$id";
$mysqli->query($sql);

header("Location: index.php");
exit;
?>