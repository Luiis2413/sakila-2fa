<?php
include '../db_connect.php';

$id = $_GET['id'];
$sql = "DELETE FROM customer WHERE customer_id=$id";
$mysqli->query($sql);

header("Location: index.php");
exit;
?>