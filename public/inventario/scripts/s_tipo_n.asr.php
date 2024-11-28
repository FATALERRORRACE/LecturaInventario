// JavaScript Document
function busqueda(){
var err,val;
err = "";
val = document.formiden.t2.value;
if(val == null || !isNaN(val) || val.length < 2 || /^\s+$/.test(val))
	err = err + "Se requiere el nombre del tipo de software. \n";
val = document.formiden.descrip.value;
if(val == null || !isNaN(val) || val.length < 10 || /^\s+$/.test(val))
	err = err + "Se requiere la descripción del tipo de software. \n";	
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}	