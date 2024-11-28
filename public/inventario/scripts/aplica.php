<?php
$query = "SELECT Id_Tipo, Tipo FROM  t_bib ORDER BY  t_bib.Tipo";
$bibliotecas = mysqli_query($conect, $query) or die(mysqli_error());
for($i = 0; $i < mysqli_num_rows($bibliotecas); $i ++)
{
	$row = mysqli_fetch_array($bibliotecas);
?>
	<option value="<?php echo $row['Id_Tipo']; ?>"><?php echo $row['Tipo']; ?></option>
<?php
}
mysqli_free_result($bibliotecas);
unset($bibliotecas, $query, $i, $row);
?>