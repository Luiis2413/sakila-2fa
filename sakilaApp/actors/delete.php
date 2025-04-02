<?php
include '../db_connect.php';

$id = $_GET['id'];
$sql = "DELETE FROM actor WHERE actor_id=$id";
$mysqli->query($sql);

header("Location: index.php");
exit;
?>