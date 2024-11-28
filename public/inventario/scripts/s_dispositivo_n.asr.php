// JavaScript Document
function busqueda(){
var op,err,val;
err = "";
val = document.formiden.t2.value;
if(val == null || !isNaN(val) || val.length < 3 || /^\s+$/.test(val))
	err = err + "Se requiere el nombre del dispositivo. \n";
val = document.formiden.descrip.value;
if(val == null || !isNaN(val) || val.length < 10 || /^\s+$/.test(val))
	err = err + "Se requiere la descripción del dispositivo. \n";	
op = document.formiden.selhard.selectedIndex;
val = document.formiden.selhard[op].value;
if(val == null || val.length < 1 || /^\s+$/.test(val))
	err = err + "No ha seleccionado el tipo de hardware. \n";
op = document.formiden.marca.selectedIndex;
val = document.formiden.marca[op].value;
if(val == null || isNaN(val) || val.length < 1 || /^\s+$/.test(val))
	err = err + "No ha seleccionado la marca del hardware. \n";	
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}	