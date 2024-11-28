<?php
class Conect {
	
	public static function conexion() {
				
		try {
		$options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		$conn = new PDO("mysql:dbname=inventario;host=localhost","root","", $options);
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
            
		return $conn;
	}

	public static function pergamum() {
				
		try 
		{
			require_once('lib/nusoap.php');
			$client = new nusoap_client("https://catalogo.biblored.gov.co/pergamum/web_service/integracao_sever_ws.php?wsdl", false);
		    //$client->soap_defencoding = 'UTF-8';
		    $client->soap_defencoding = 'iso-8859-1';
		} 
		catch (SoapFault $client) 
		{
			echo "<h4 align='center'>Sin acceso a los servicios ...</h4>";
		}
		return $client;
	}
}
?>