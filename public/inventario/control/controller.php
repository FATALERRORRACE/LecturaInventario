<?php

class Inventario {

	public function plantilla() {

		include("vista/template.php");
	}

	public function cargaNavegacion(){
		if(isset($_GET['ruta']))
		{

			$respuesta = NavegaPaginas::cargaPaginas($_GET['ruta']);
			include($respuesta);
		}

	}

	public static function registroUsuario(){
		$msj = '';
		$xmlr = array('cod_pessoa' => "".$_POST['f2']."",
           'senha_pessoa' => "".$_POST['f1']."",
           'chave' => "a8ad1f47da75e86d6511235a6a6c3b7d");

		$respuesta = Process::registroUsuarioModel($xmlr);

		if(isset($respuesta->usuario->nome_pessoa) && strlen($respuesta->usuario->nome_pessoa) > 5) 
		{
			$cambio = array('?','Ã','Ã`');
			$respuesta->usuario->nome_pessoa = str_replace($cambio, array("Ñ"), $respuesta->usuario->nome_pessoa);
			$_SESSION['MM_Bib_Docum'] = $_POST['f2'];
			$_SESSION['MM_Bib_User'] = strtoupper(trim($respuesta->usuario->nome_pessoa));
			$_SESSION['MM_Bib_Biblo'] = strtoupper($respuesta->usuario->unidade_informacao);
			$_SESSION['MM_Bib_Regis'] = '';
			$_SESSION['MM_Bib_Acces'] = TRUE;
			$msj = 'OK';
		}
		else
		{
			echo $respuesta;	
			$_SESSION['MM_Bib_Acces'] = FALSE;	
		}

	}

	public static function vistaBibliotecas(){

		$sql =	"SELECT Nombre, Tabla FROM bibliotecas WHERE Fecha_Inventario = '".date('Y-n-j')."'";
		$respuesta = Process::ServicioBibliotecasModel($sql);
		//echo 'registros: '.sizeof($respuesta);
		if(sizeof($respuesta) == 0)
		{
			echo "<h4>No hay bibliotecas disponibles para el proceso de inventario.</h4>";
		}
		else
		{
			echo "<select id='selBiblioteca' name='selBiblioteca'>";	
        
  			foreach($respuesta as $row => $item)
  			{ 
  				echo "<option value='". $item['Nombre'] ."'>". $item['Nombre']. "</option>";
  			}		
           echo "</select>"; 
		}
	}

}
?>