<?php
if(!isset($_SESSION)) 
{
  	session_start();
}
if(!isset($_SESSION['MM_Bib_User']))
{
	echo "<h4 align='center'>Sesión finalizada, por favor vuelva a ingresar sus datos de Pergamum.</h4>";
	echo "<h4 align='center'><a href='?ruta=inicio'>Registro</a></h4>";

}
?>