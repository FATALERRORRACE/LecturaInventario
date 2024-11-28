<?php
if($_SESSION['Total_Regs'] > $maxRows)
{
?>
<hr align="center" width="40%" />
<form name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input name="reg_s" type="hidden" value="<?php echo $reg_s; ?>" />
<input name="maximos" type="hidden" value="<?php echo $maxRows; ?>" />
<input name="totalregs" type="hidden" value="<?php echo $totalregs; ?>" />
<input name="actual" type="hidden" value="" />
<?php echo $pag; ?>
<input type="button" name="actual_a" value="Anteriores" class="submit_s" onClick="sig('Anteriores');" />&nbsp;&nbsp;
<input type="button" name="actual_s" value="Siguientes" class="submit_s" onClick="sig('Siguientes');" />
</form>
<?php 
}
unset($pag, $reg_s, $totalregs, $maxRows, $startRow);
?>