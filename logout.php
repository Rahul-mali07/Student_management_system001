<?php
session_start();

/* ================================
   DESTROY SESSION
   ================================ */
session_unset();
session_destroy();

/* ================================
   REDIRECT TO HOME PAGE
   ================================ */
header("Location: index.php");
exit();
?>
