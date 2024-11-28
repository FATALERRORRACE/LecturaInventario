// JavaScript Document
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
var err,val,op;
err = "";
op = document.formiden.selprov.selectedIndex;
if((op == null || op == 0) && !val)
	err += "No ha seleccionado el proveedor. \n";	
else
	document.formiden.proveedor.value = document.formiden.selprov[op].text;
val = document.formiden.t1.value;
if(val == null || !isNaN(val) || val.length < 3 || /^\s+$/.test(val))
	err += "Se requiere el nombre del contacto. \n";
val = document.formiden.t2.value;
if(!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w-*/.test(val)))
	err += "Se requiere el correo electrónico del contacto. \n";
val = document.formiden.t3.value;
if(val == null || val.length < 4 || /^\s+$/.test(val))
	err += "Se requiere el cargo del contacto. \n";
val = document.formiden.t4.value;
if(valida_tel(document.formiden.t4.value) == 0 && valida_tel(document.formiden.t5.value) == 0)
	err += "Se requiere de al menos un número telefónico del contacto. \n";
else
{
	val = document.formiden.t4.value;
    if(val.length > 0 && valida_tel(val) == 0)
	err += "Verifique el número telefónico. \n";
    val = document.formiden.t5.value;
    if(val.length > 0 && valida_tel(val) == 0)
        err += "Verifique el número de celular. \n";
}
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}