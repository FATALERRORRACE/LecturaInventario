// JavaScript Document
function cam_bib(w) {
document.registro.biblioteca.value = document.registro.sel_bib[w].text;
}
function validar() {
var val, err;
err = "";
val = document.registro.sel_bib.selectedIndex;
if(val == null || val == 0)
	err = err + "Se requiere la biblioteca. \n";
val = document.registro.usuario.value;
if(val == null || val.length < 4 || /^\s+$/.test(val))
	err = err + "Se requiere el nombre de usuario. \n";
val = document.registro.serial.value;
if(val == null || val.length < 4 || /^\s+$/.test(val))
	err = err + "Se requiere la contraseña de usuario. \n";
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n \n"+err+"\n");
	return false; 
}	
else
	return true;
}