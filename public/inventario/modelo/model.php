<?php

class NavegaPaginas {

	public static function cargaPaginas($ir)
	{
		$pag = '';
		if($ir == 'inicio')
		{
			$pag = 'vista/modulos/sesion.php';
		}
		else if($ir == 'lectura')
		{
			$pag = 'vista/modulos/leer.php';
		}
		else if($ir == 'avance')
		{
			$pag = 'vista/modulos/resultado.php';
		}
		else if($ir == 'resultado')
		{
			$pag = 'vista/modulos/informes.php';
		}
		else if($ir == 'salir')
		{
			$pag = 'vista/modulos/salir.php';
		}
		return $pag;
	}
}
?>