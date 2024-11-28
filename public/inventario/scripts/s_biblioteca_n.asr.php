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
	telef = telef.split("/");
	if(telef.length > 2)
		ret = 0;
	else if(isNaN(telef[0]) || (telef[0].length != 7 && telef[0].length != 10) || telef[0] < 0)
		ret = 0;
	if(telef.length == 2 && (isNaN(telef[1]) || telef[1].length < 2 || telef[1].length > 10 || telef[1] < 0))
		ret = 0;
	return ret;
}
function horario(hrio)
{
	var ret = 1;
	hrio = hrio.split(":");
	if(hrio.length != 2)
		ret = 0;
	else
    {
        if(isNaN(hrio[0]) || (hrio[0] <= 0 || hrio[0] > 12))
            ret = 0;
        if(isNaN(hrio[1]) || (hrio[1] < 0 || hrio[1] >= 60))
            ret = 0;
	}
    return ret;
}
function busqueda(){
var err,val;
err = "";
val = document.bibliote.tipo_b.selectedIndex;
if(val == null || val == 0)
	err += "Se requiere el tipo de bilioteca. \n";
val = con_min(document.bibliote.t2.value);
if(val == null || !isNaN(val) || val.length < 3 || /^\s+$/.test(val))
	err += "Se requiere el nombre de la bilioteca. \n";
document.bibliote.t2.value = val;
val = con_min(esp_blanco(document.bibliote.mb.value));
if(!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w-*/.test(val)))
	err += "Se requiere el correo electrónico de la biblioteca. \n";
document.bibliote.mb.value = val;
val = con_min(document.bibliote.dir.value);
if(val == null || val.length < 8 || /^\s+$/.test(val))
	err += "Se requiere completar la dirección de la biblioteca. \n";
val = con_min(esp_blanco(document.bibliote.t4.value));
if(valida_tel(val) == 0)
	err += "Se requiere de al menos un número telefónico de la biblioteca. \n";
document.bibliote.t4.value = val;
val = con_min(document.bibliote.t3.value);
if(val == null || val.length < 8 || /^\s+$/.test(val))
	err += "Se requiere del nombre del director/coordinador de la biblioteca. \n";
val = con_min(esp_blanco(document.bibliote.md.value));
document.bibliote.md.value = val;
if((val) && (!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w-*/.test(val))))
	err += "Se requiere el correo electrónico del director/coordinador la biblioteca. \n";
val = con_min(esp_blanco(document.bibliote.cel.value));
document.bibliote.cel.value = val;
if(val.length > 0 && valida_tel(val) == 0)
	err += "Verifique el celular del director / coordinador. \n";
document.bibliote.al.value = esp_blanco(document.bibliote.al.value);
document.bibliote.cl.value = esp_blanco(document.bibliote.cl.value);
if(horario(document.bibliote.al.value) == 0 || horario(document.bibliote.cl.value) == 0)
	err += "Verifique la hora de apertura y cierre la biblioteca el lunes. \n";
document.bibliote.amv.value = esp_blanco(document.bibliote.amv.value);
document.bibliote.cmv.value = esp_blanco(document.bibliote.cmv.value);
if(horario(document.bibliote.amv.value) == 0 || horario(document.bibliote.cmv.value) == 0)
	err += "Verifique la hora de apertura y cierre la biblioteca de martes a viernes. \n";
document.bibliote.os.value = esp_blanco(document.bibliote.os.value);
document.bibliote.cs.value = esp_blanco(document.bibliote.cs.value);
if(horario(document.bibliote.os.value) == 0 || horario(document.bibliote.cs.value) == 0)
	err = err + "Verifique la hora de apertura y cierre la biblioteca el sábado. \n";
document.bibliote.ad.value = esp_blanco(document.bibliote.ad.value);
document.bibliote.cd.value = esp_blanco(document.bibliote.cd.value);
if((document.bibliote.ad.value.length > 0 || document.bibliote.cd.value.length > 0) && (horario(document.bibliote.ad.value) == 0 || horario(document.bibliote.cd.value) == 0))
	err += "Verifique la hora de apertura y cierre la biblioteca el domingo. \n";	
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}