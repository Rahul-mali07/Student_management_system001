<<<<<<< HEAD
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
=======
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
>>>>>>> 37ab6df4249719db4fc9fefb4872290fa0452799
