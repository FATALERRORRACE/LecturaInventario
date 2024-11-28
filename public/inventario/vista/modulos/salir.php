<h1>Se ha cerrado la sesión y borrado los datos temporales</h1>

<h1>Puede iniciar sesión de nuevo</h1>
<?php

unset($_SESSION);
session_destroy();
header("location:index.php");
?>