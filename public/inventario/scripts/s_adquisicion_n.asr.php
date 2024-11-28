// JavaScript Document
function cal_fecha(fecha)
{
	var ret = 0;
	var f_a = document.formulario.f_act.value;
	f_a = f_a.split("-");
	fecha = fecha.split("-");
    if(fecha[1].charAt(0) == 0)
		fecha[1] = fecha[1].charAt(1);
	if(fecha[2].charAt(0) == 0)
		fecha[2] = fecha[2].charAt(1);
    ret = parseInt(f_a[0]) - parseInt(fecha[0]);
	if(parseInt(fecha[1]) > parseInt(f_a[1]) || (parseInt(fecha[1]) == parseInt(f_a[1]) && parseInt(fecha[2]) > parseInt(f_a[2])))
		ret --;
	return ret;
}

function busqueda(){
var op,err,valor;
err = "";
valor = document.formulario.fecha.value;
if(valor == null || valor.length < 6 || /^\s+$/.test(valor))
	err = err + "Se requiere la fecha de adquisición. \n";
else if(cal_fecha(valor) < 0)
	err = err + "La fecha de adquisición no es válida,\n no puede ser superior a la actual. \n";
valor = document.formulario.f_adquisicion.value;
if(valor == null || valor.length < 6 || /^\s+$/.test(valor))
	err = err + "Se requiere la fecha de ingreso. \n";
else if(cal_fecha(valor) < 0)
	err = err + "La fecha de ingreso no es válida,\n no puede ser superior a la actual. \n";
op = document.formulario.t_adq.selectedIndex;
valor = document.formulario.t_adq[op].value;
if(valor == null || valor.length < 1 || /^\s+$/.test(valor) || valor <= 0)
	err = err + "Se requiere el tipo de aquisición. \n";	
op = document.formulario.selcont.selectedIndex;
if(document.formulario.selprov.selectedIndex > 0 && (op == null || op == 0))
	err = err + "Se requiere el contacto / proveedor. \n";
valor = document.formulario.factura.value;
if(valor == null || valor.length < 3 || /^\s+$/.test(valor))
	err = err + "Se requiere el número de la factura. \n";
valor = document.formulario.disp.value;
if(valor == null || valor.length < 3 || /^\s+$/.test(valor))
	err = err + "Se requiere el tipo de dispositivo. \n";
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false;
}	
else
	return true;
}	
function agregar() {
var est = false;
var op = document.formulario.dispositivo.selectedIndex;
if(op > 0)
{
	var val = document.formulario.dispositivo[op].text;
	var cadena = document.formulario.disp.value.split(", ");
	for(w = 0; w < cadena.length; w ++)
	{
        if(cadena[w] == val)
			est = true;
	}
	if(est == false)
	{
		if(document.formulario.disp.value.length > 1)
			document.formulario.disp.value = document.formulario.disp.value + ", " + val;
		else
			document.formulario.disp.value = document.formulario.disp.value + val;
	}
}
}
function borrar() {
var valor = "";
document.formulario.disp.value = valor;
}