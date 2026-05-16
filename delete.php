<?php
require_once 'auth_check.php';
require_admin();
require_once 'db.php';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);
    $id    = (int)$_GET['id'];

    $allowed_tables = ['categories', 'customers', 'products', 'orders', 'complains', 'order_items', 'payments', 'reviews'];

    if (in_array($table, $allowed_tables)) {
        $sql = "DELETE FROM $table WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            header("Location: menu.php?deleted=1");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid table name!";
    }
} else {
    header("Location: menu.php");
    exit();
}
?>
