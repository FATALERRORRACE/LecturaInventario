<?php include("asignacion.php"); ?>
<tr>
  <td width="40%" align="right" class="bg1">Asignado a:&nbsp;</td>
  <td width="60%" align="left" class="blanco">
  <select name ="Asignado" id="Asignado" class="objeto_1_1" title="Disponible, Publico, Administrativo">
  <option value="">Seleccione la asignaci&oacute;n</option>
  <?php
  for($q=0;$q<sizeof($asignacion);$q++)
  { ?>
    <option value="<?php echo $q; ?>" <?php if($row['Asignado'] == $q) echo "selected='selected'"; ?>>
	<?php echo $asignacion[$q]; ?></option>
<?php } ?>
  </select>
  <script language="javascript">getcamp('Asignado');</script>
  </td>
</tr>
<?php unset($q, $asignacion); ?>