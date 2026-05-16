<?php
require_once 'auth_check.php';
session_destroy();
header("Location: ../index.php");
exit();
?>
