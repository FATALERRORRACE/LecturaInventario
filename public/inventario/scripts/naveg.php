<?php
if(!isset($_POST["totalregs"]))
{
	$bibliotecas = mysqli_query($conect, $query) or die(mysqli_error());
	$_SESSION['Total_Regs'] = mysqli_data_seek($bibliotecas,0);
}
else
{
	if(isset($_POST["reg_s"]))
	{
		$_SESSION['Reg_S'] = intval($_POST["reg_s"]);
	}
	
}
if(isset($_POST["n_reg"]) && intval($_POST["n_reg"]) > 0)
{
	$_SESSION['Reg_S'] = intval($_POST["n_reg"]);
}
$startRow = $_SESSION['Reg_S'] * $maxRows;
$_SESSION['MM_Regs_Inf'] = $_SESSION['Total_Regs'];
if($_SESSION['Total_Regs'] > 0)
{
$_SESSION['MM_Regs_Inf'] .=".  Registro ".($startRow + 1)." Al ";
if($maxRows * ($_SESSION['Reg_S'] + 1) > $_SESSION['Total_Regs'])
	$_SESSION['MM_Regs_Inf'] .= ($maxRows * ($_SESSION['Reg_S'] + 1) - (($maxRows * ($_SESSION['Reg_S'] + 1)) - $_SESSION['Total_Regs']));
else
	$_SESSION['MM_Regs_Inf'] .= ($maxRows * ($_SESSION['Reg_S'] + 1));
}
?>