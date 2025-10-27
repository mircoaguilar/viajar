<?php
session_start();

// Destruir sesión completamente
session_unset();
session_destroy();

// Redirigir con toast informativo
header("Location: ../../index.php?page=login&message=" . urlencode("Sesión cerrada correctamente.") . "&status=info");
exit;
?>
