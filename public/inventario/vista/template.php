<?php
if(!isset($_SESSION)) 
{
  	session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Inventario bibliográfico</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/estilos.css" rel="stylesheet" type="text/css" />  
 
</head>
<body>

<header>
		<h1 align="center"><img src="img/loader.gif"></h1>
		
</header>

<h3 align="center">
<?php	
if(isset($_SESSION['MM_Bib_User']))
{
	echo 'Colaborador:  ' .$_SESSION['MM_Bib_User'];
	echo '<br />';
	echo 'Biblioteca: '.$_SESSION['MM_Bib_Regis'];
}	
else
{
	echo 'Bienvenido, inicie sesion	para continucar.';
}
?>
</h3>

<nav>
	<ul>
		<?php
		if(!isset($_SESSION['MM_Bib_User']))
		{ ?>	
		<li><a href="?ruta=inicio">Iniciar sesión</a></li>
		<?php
		}
		else
		{ ?>
		<li><a href="?ruta=lectura">Iniciar inventario</a></li>
		<li><a href="?ruta=avance">Ver avances</a></li>
		<li><a href="?ruta=resultado">Informes</a></li>
		<li><a href="?ruta=salir">Salir</a></li>
		<?php } ?>

	</ul>
</nav>
<?php 
	//include "modules/navegacion.php";
?>

<section>

<?php

$mvc = new Inventario();
$mvc -> cargaNavegacion();

?>
	
</section>

</body>
</html>