<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Doctor ID is missing.');
}

$id = (int)$_GET['id']; // Make sure $id is an integer
$db = Database::getInstance();

$sql = "DELETE FROM doctors WHERE id = ?";
$params = [$id];

// Ensure the query and params are correctly passed
$result = $db->query($sql, $params);

if ($result) {
    header("Location: view_doctors.php?msg=deleted");
    exit();
} else {
    die("Error deleting doctor.");
}

require_once 'includes/footer.php';
?>