<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    echo "Access denied!";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM records WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: view_records.php");
exit();
?>
