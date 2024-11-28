// JavaScript Document
function busqueda(){
var err,val,op;
err = "";
val = document.defus.nombre.value;
val = val.toLowerCase();
document.defus.nombre.value = val;
if(val == null || !isNaN(val) || val.length < 5 || /^\s+$/.test(val))
	err = err + "Se requiere el nombre del usuario. \n";
val = document.defus.docs.value;
val = val.toLowerCase();
document.defus.docs.value = val;
if(val == null || val.length < 4 || /^\s+$/.test(val))
	err = err + "Se requiere el usuario de acceso. \n";
val = document.defus.contr.value;
val = val.toLowerCase();
document.defus.contr.value = val;
if(val == null || val.length < 5 || /^\s+$/.test(val))
	err = err + "Se requiere de la contraseña (mínimo 5 caracteres). \n";
op = document.defus.priv.selectedIndex;
if((op == null || op == 0))
	err = err + "No ha seleccionado los privilegios. \n";
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}