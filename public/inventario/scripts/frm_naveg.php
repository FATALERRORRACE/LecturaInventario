<?php
if(/*$totalregs*/$_SESSION['Total_Regs'] > $maxRows)
{
?>
<hr align="center" width="40%" />
<form name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input name="reg_s" type="hidden" value="<?php echo $_SESSION['Reg_S']; /*$reg_s;*/ ?>" />
<input name="maximos" type="hidden" value="<?php echo $maxRows; ?>" />
<input name="totalregs" type="hidden" value="<?php echo $_SESSION['Total_Regs']; /*$totalregs;*/ ?>" />
<?php echo $pag; ?>
<input type="button" name="actual_a" value="Anteriores" class="submit" onClick="sig('Anteriores');" />&nbsp;&nbsp;
<input type="button" name="actual_s" value="Siguientes" class="submit" onClick="sig('Siguientes');" />
<br />
<?php
if(/*$totalregs*/$_SESSION['Total_Regs'] > $maxRows)
{ ?>
<strong>Ir al conjunto de registros:&nbsp;</strong>
<select name ="n_reg" onChange ="submit();" class="body_r">
<option value="-1" selected="selected">&nbsp; &nbsp; <?php echo ($_SESSION['Reg_S'] + 1); ?>&nbsp; &nbsp;</option>
<?php
for($w = 0; $w <= (intval(/*$totalregs*/$_SESSION['Total_Regs'] / $maxRows)); $w ++)
{ ?>
    <option value ="<?php echo $w; ?>">&nbsp;&nbsp;<?php echo ($w + 1); ?>&nbsp; &nbsp;</option>
<?php } ?>
</select>
<?php } ?>
</form>
<?php 
}
unset($pag, /*$reg_s, $totalregs,*/ $maxRows, $startRow);
?>