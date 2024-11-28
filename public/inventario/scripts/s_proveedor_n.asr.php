// JavaScript Document
function con_min(w) {
return w.toLowerCase();
}
function valida_tel(telef)
{
var ret = 1;
telef = telef.replace(/[ "]/g,"");
telef = telef.split("/");
if(telef.length > 2)
    ret = 0;
else if(isNaN(telef[0]) || (telef[0].length != 7 && telef[0].length != 10) || telef[0] < 0)
    ret = 0;
if(telef.length == 2 && (isNaN(telef[1]) || telef[1].length < 2 || telef[1].length > 10 || telef[1] < 0))
    ret = 0;
return ret;
}
function busqueda(){
var err,val;
err = "";
val = con_min(document.proveed.t2.value);
if(val == null || !isNaN(val) || val.length < 3 || /^\s+$/.test(val))
	err += "Se requiere el nombre del proveedor. \n";
document.proveed.t2.value = val;
val = con_min(document.proveed.mb.value);
if(!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w-*/.test(val)))
	err += "Se requiere el correo electrónico del proveedor. \n";
document.proveed.mb.value = val;
val = con_min(document.proveed.dir.value);
if(val.length > 0 && /^\s+$/.test(val))
	err += "Se requiere completar la dirección del proveedor. \n";
document.proveed.dir.value = val;
val = document.proveed.t4.value;
if(val.length > 0 && valida_tel(val) == 0)
	err += "Se requiere de al menos un número telefónico del proveedor. \n";
val = document.proveed.cel.value;
if(val.length > 0 && valida_tel(val) == 0)
	err += "Verifique el número de celular. \n";
if(!document.proveed.radio[0].checked && !document.proveed.radio[1].checked && !document.proveed.radio[2].checked && !document.proveed.radio[3].checked)
	err += "Verifique los servicios del proveedor. \n";
document.proveed.web.value = con_min(document.proveed.web.value);
/* val = document.proveed.t3.value;
if(val == null || val.length < 4 || /^\s+$/.test(val))
	err += "Se requiere del Nit del proveedor. \n"; */
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}