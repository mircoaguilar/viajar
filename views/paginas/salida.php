<?php
session_start();

session_unset();
session_destroy();

header("Location: ../../index.php?page=login&message=" . urlencode("Sesión cerrada correctamente.") . "&status=info");
exit;
?>
