// JavaScript Document
function con_min(w) {
return w.toLowerCase();
}
function busqueda(){
var err,val,op;
err = "";
val = con_min(document.formiden.t2.value);
if(val == null || !isNaN(val) || val.length < 5 || /^\s+$/.test(val))
	err = err + "Se requiere el nombre de la edición. \n";
document.formiden.t2.value = val;
op = document.getElementById("licent").selectedIndex;
val = document.getElementById("licent")[op].value;
if(val == null || isNaN(val) || val.length < 1 || /^\s+$/.test(val))
	err = err + "Se requiere el tipo de licencia. \n";
op = document.getElementById("t_soft").selectedIndex;
val = document.getElementById("t_soft")[op].value;
if(val == null || isNaN(val) || val.length < 1 || /^\s+$/.test(val))
	err = err + "Se requiere el tipo de software. \n";
document.formiden.cantidad.value = parseInt(document.formiden.cantidad.value);
val = document.formiden.cantidad.value;
if(val == null || isNaN(val) || val < 0 || /^\s+$/.test(val))
	err = err + "Se requiere la cantidad de licencias. \n";	
if(document.formiden.disp1.checked == false && document.formiden.disp2.checked == false)
	err = err + "Se requiere la disponibilidad del software. \n";	
else
{
    if(document.formiden.disp1.checked)
        document.formiden.disponible.value = document.formiden.disp1.value;
    else
        document.formiden.disponible.value = document.formiden.disp2.value;
}
val = document.formiden.descrip.value;
if(val == null || !isNaN(val) || val.length < 10 || /^\s+$/.test(val))
	err = err + "Se requiere la descripción de la edición. \n";	
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}	