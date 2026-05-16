<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_manager() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php?error=Please login first.");
        exit();
    }
    if (!in_array($_SESSION['role'], ['manager', 'admin'])) {
        header("Location: ../index.php?error=Access denied.");
        exit();
    }
}

function is_logged_in() { return isset($_SESSION['user_id']); }
function get_role() { return $_SESSION['role'] ?? ''; }
?>
