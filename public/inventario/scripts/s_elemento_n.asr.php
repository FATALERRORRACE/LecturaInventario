// JavaScript Document
function busqueda()
{
var op, val, err;
err = "";
op = window.parent.adquirir.document.adquisicion.sel_adquis.selectedIndex;
if(op == null || op == 0)
		err = err + "No se ha seleccionado la adquisición. \n";
else
{
	document.consolidado.adq.value = window.parent.adquirir.document.adquisicion.sel_adquis.options[op].value;
	op = window.parent.disposit.document.selecdispo.sel_disp.selectedIndex;
	if(op == null || op == 0)
			err = err + "No se ha seleccionado el dispositivo. \n";
	else
		document.consolidado.dis.value = window.parent.disposit.document.selecdispo.sel_disp.options[op].value;
}
val = window.parent.config.document.configuracion.serial.value;
if(val == null || val.length < 4 || /^\s+$/.test(val))
		err = err + "No ha ingresado el serial del dispositivo. \n";
else
	document.consolidado.ser.value = window.parent.config.document.configuracion.serial.value;
val = window.parent.config.document.configuracion.parte.value;
if(val.length > 0 && /^\s+$/.test(val))
		err = err + "No ha ingresado el número de parte. \n";
else
	document.consolidado.parte.value = window.parent.config.document.configuracion.parte.value;
val = window.parent.config.document.configuracion.placa.value;
if(val.length > 0 && /^\s+$/.test(val))
		err = err + "No ha ingresado el número de placa SED. \n";
else
	document.consolidado.pla.value = window.parent.config.document.configuracion.placa.value;
op = window.parent.config.document.configuracion.estado.selectedIndex;
if(op == null || op == 0)
		err = err + "No ha seleccionado el estado del dispositivo. \n";
else
	document.consolidado.est.value = window.parent.config.document.configuracion.estado.options[op].value;
op = window.parent.config.document.configuracion.selbib.selectedIndex;
if(op == null || op == 0)
		err = err + "No ha seleccionado la biblioteca de destino. \n";
else
	document.consolidado.bib.value = window.parent.config.document.configuracion.selbib.options[op].value;
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}	
else 
	return true; 	
}
function correcto()
{
	alert('\n Se ha ingresado el registro satisfactóriamente.\n');
    window.parent.config.document.configuracion.serial.value = "";
    window.parent.config.document.configuracion.parte.value = "";
    window.parent.config.document.configuracion.placa.value = "";
    window.parent.config.document.configuracion.estado.selectedIndex = 0;
    window.parent.config.document.configuracion.selbib.selectedIndex = 0;
}