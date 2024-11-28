<h1>Lectura de códigos de barras</h1>
<?php
include('sesion.php');
?>
<?php
if(isset($_POST['selBiblioteca'], $_POST['enviar']))
{ 
  
  $_SESSION['MM_Bib_Regis'] = $_POST['selBiblioteca'];
  
}
?>

<form method="post" action="?ruta=lectura">
        <div>Bibliotecas disponibles para inventario:</div>
        <div>
        <?php
        $registro = new Inventario();
		$registro -> vistaBibliotecas();
        
		?>    	
        </div>	
          
	<p align="center"><input type="submit" name="enviar" id="enviar"></p>
</form>
       <br /> <br /> 
