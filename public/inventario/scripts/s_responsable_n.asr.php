// JavaScript Document
function con_min(w) {
return w.toLowerCase();
}
function esp_blanco(txt) {
if(txt.length > 0)
	return txt.replace(/[" "]/g, "");
else
	return txt;
}
function valida_tel(telef)
{
	var ret = 1;
    var i = 0;
	telef = telef.split("/");
	if(telef.length > 4)
		ret = 0;
	else
    {
        for(i=0;i<telef.length;i++)
        {
            if(isNaN(telef[i]) || (telef[i].length != 7 && telef[i].length != 10 && telef[i].length != 3 && telef[i].length != 4 && telef[i].length != 5) || telef[i] < 0)
                ret = 0;
        }
	}
    return ret;
}
function busqueda(){
var op,err,val;
err = "";
op = document.formulario.cargo.selectedIndex;
val = document.formulario.cargo[op].value;
if(val == null || val.length == 0 || /^\s+$/.test(val) || isNaN(val))
	err += "Se requiere el cargo. \n";	
val = con_min(document.formulario.nombre.value);
if(val == null || !isNaN(val) || val.length < 7 || /^\s+$/.test(val))
	err += "Se requiere el nombre del responsable. \n";
document.formulario.nombre.value = val;
val = con_min(esp_blanco(document.formulario.correo.value));
if((val.length > 0) && (!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w-*/.test(val))))
	err += "Se requiere el correo electrónico del responsable. \n";
document.formulario.correo.value = val;
val = esp_blanco(document.formulario.tel1.value);
document.formulario.tel1.value = val;
if(val.length > 0 && valida_tel(val) == 0)
	err += "El número telefónico 1 no es válido. \n";
val = esp_blanco(document.formulario.tel2.value);
document.formulario.tel2.value = val;
if(val.length > 0 && valida_tel(val) == 0)
	err += "El número telefónico 2 no es válido. \n";
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}