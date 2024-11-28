<?php
if(!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_Biblio']))
{ ?>
<h4 align="center">Han pasado m&aacute;s de 25 minutos sin actividad, por seguridad la sesi&oacute;n se ha cerrado.</h4>
<?php
if(strpos($_SERVER['PHP_SELF'],"admin.php"))
{ ?>
	<h4 align="center"><a href="index.php">Inicie sesi&oacute;n de nuevo...</a></h4>
<?php
}
else
{ ?>
	<h4 align="center"><a href="../index.php" target="_parent">Inicie sesi&oacute;n de nuevo.</a></h4>
<?php
}
exit;
}
include("filtr_ind.php");	
?>