<?php
// public/auth/logout.php - Logout unificado
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>
