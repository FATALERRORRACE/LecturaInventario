<?php
function quitar_caracter($string) {
   $string = strip_tags($string);
   return str_replace(array("<",">","?","\\",";","'","$","{","}","&","[","]"), "", $string);
}
foreach($_POST as $key=>$val){ 
   $_POST[$key] = quitar_caracter($val);
}  
foreach($_GET as $key=>$val){ 
   $_GET[$key] = quitar_caracter($val);
}
?>