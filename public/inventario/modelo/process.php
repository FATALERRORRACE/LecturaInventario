<?php

//Registro de peticiones a la DB
require_once("conect/connec.php");
class Process extends Conect {

	public static function registroUsuarioModel($usuario){
		
		//var_dump($usuario);
		$result = Conect::pergamum()->call('ws_autentica_usuario', $usuario);
		$result = iconv('ISO-8859-1', 'UTF-8', $result); // o utf8_decode($result);
		$err = Conect::pergamum()->getError();
		$array = simplexml_load_string($result); //nusoap
		if($err)
		{ 
			return "Estimado(a) usuario(a), en este momento no podemos validar tu solicitud, pedimos excusas por el inconveniente, por favor intenta de nuevo en unos minutos";
		}
		else if(isset($array->usuario->nome_pessoa) && strlen($array->usuario->nome_pessoa) > 5)
		{
			return $array;
		} 
		else
		{
		return "El número de documento y/o contraseña no es válido, si no recuerdas tu contraseña ingresa a Actualiza/Recupera tu contraseña, sigue el instructivo en <a href='https://www.biblored.gov.co/afiliese-a-biblored'> Afíliate</a>.<br />Si requieres ayuda por favor comunícate con el Centro de Atención al Usuario (CAU) al 5803050 opción 1 o en nuestro chat/WhatsApp en línea.<br />
			<div align=\"center\">...</div>";
		}
	}

	public static function ServicioBibliotecasModel($sql) {
		//echo $sql;
        $query = Conect::Conexion()->prepare($sql);
        $query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC); 
		$query->close();
	}
	
	public static function RegistroPlacaModel($sql) {
		
		$stmt = Conect::Conexion()->prepare($sql);
		$stmt->bindParam(":correo", $datos['correo'], PDO::PARAM_STR);
		if($stmt->execute())
		{
			
		}
		else
		{
			
		}
		return $estado;
		$stmt->close();
	}
	
}



?>