<option value="">Seleccione la biblioteca</option>
<?php
$query = "SELECT Id_Biblioteca, Nombre_Biblioteca FROM biblioteca ORDER BY biblioteca.Nombre_Biblioteca";
$bibliotecas = mysqli_query($conect, $query) or die(mysqli_error());
for($i = 0; $i < mysqli_num_rows($bibliotecas); $i ++)
{
	$row = mysqli_fetch_array($bibliotecas);
?>
	<option value="<?php echo $row['Id_Biblioteca']; ?>" <?php if(isset($_SESSION['MM_Biblio']) && $_SESSION['MM_Biblio'] == $row['Id_Biblioteca']) echo "selected='selected'"; ?>>
	<?php echo $row['Nombre_Biblioteca']; ?></option>
<?php
}
mysqli_free_result($bibliotecas);
unset($bibliotecas, $query, $i, $row);
?>