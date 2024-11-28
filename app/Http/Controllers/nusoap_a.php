<?php  
try 
{
	include(__DIR__.'/nusoap.php');
	$client = new nusoap_client("https://catalogo.biblored.gov.co/pergamum/web_service/integracao_sever_ws.php?wsdl", false);
} 
catch (SoapFault $client) 
{
	echo "<h4 align='center'>Sin acceso a los servicios ...</h4>";
	exit();	
}
?>