<h1>Iniciar sesión con la cuenta de Pergamum</h1>


<form method="post" action="?ruta=inicio">
        <div>Nombre de usuario:<br />
            <input type="text" name="f2" id="f2" required value="">	
        </div>	
        <div >Contraseña:<br />
            <input type="text" name="f1" id="f1" required value="">	
        </div>    
	<p align="center"><input type="submit" name="enviar" id="enviar"></p>
</form>
<br /> <br /> 
 
<?php
if(isset($_POST['f2'], $_POST['f1'], $_POST['enviar']))
{ 
  
  $registro = new Inventario();
  $registro -> registroUsuario();
  header("location:index.php");
}
?>  