// JavaScript Document
var camps = new Array();
camps[0] = "Id";
camps[1] = "Integrado";
camps[2] = "Marca";
camps[3] = "Dispositivo";
camps[4] = "Biblioteca";
camps[5] = "Area";
camps[6] = "Adquisicion";
camps[7] = "Serial";
camps[8] = "Estado";
var normalize = (function() {
  var from = "ãàáäâèéëêìíïîòóöôùúüûÇçªº", 
      to   = "aaaaaeeeeiiiioooouuuuccoo",
      mapping = {};
  for(var i = 0, j = from.length; i < j; i++ )
      mapping[ from.charAt( i ) ] = to.charAt( i );
  return function( str ) {
      var ret = [];
      for( var i = 0, j = str.length; i < j; i++ ) {
          var c = str.charAt( i );
          if( mapping.hasOwnProperty( str.charAt( i ) ) )
              ret.push( mapping[ c ] );
          else
              ret.push( c );
      }      
      return ret.join( '' );
  }
})();
//eliminar comillas y pasar a mayúsculas
function quit_com(txt_dat) {
txt_dat = normalize(txt_dat);
return txt_dat;
}
function verifica() {
if(document.bootcamp.disp.value.length > 1 && confirm("¿Realmente desea eliminar los campos?\n"))
	return true;
else
	return false;
}
function find_cam(z) {
var ret = true;
for(w = 0; w < camps.length; w ++)
{
	if(z == camps[w])
    {
        alert("Este campo no se puede eliminar");
        ret = false;
        break;
    }
}
return ret;
}
function query(y,z) {
var cadena = "";
var chk;
chk = document.getElementById('chk_'+y);
if(find_cam(chk.value))
{
    for(w = 0; w < z; w ++)
    {
        chk = document.getElementById('chk_'+w);
        if(chk.checked == true)
            cadena+=chk.value+",";
    }
    cadena = cadena.substring(0,(cadena.length - 1));
    document.bootcamp.disp.value = cadena;
}
else
	chk.checked = false;
}
function busqueda(){
var valor, err, op;
err="";
valor = document.hardw.t2.value;
if(valor == null || !isNaN(valor) || valor.length < 2 || /^\s+$/.test(valor))
{	
	err+="Debe ingresar el nombre del campo.\n";
	document.hardw.t2.focus();
}
else
{
	valor = valor.toLowerCase();
	valor = valor.replace(/[ "]/g, "");
	valor = quit_com(valor);
	err = valor.charAt(0);
	err = err.toUpperCase();
	valor = valor.substring(1);
	valor =  err+valor;
	document.hardw.t2.value = valor;
    err = "";
}
valor = document.hardw.c2.value;
if(valor == null || !isNaN(valor) || valor.length < 2 || /^\s+$/.test(valor))
{	
	err+="Debe ingresar el título del campo.\n";
	document.hardw.c2.focus();
}
else
{
	valor = valor.toLowerCase();
    op = valor.charAt(0);
	op = op.toUpperCase();
	valor = valor.substring(1);
	valor =  op+valor;
	document.hardw.c2.value = valor;
    op = "";
}
op = document.hardw.dato.selectedIndex;
if(op == 0)
	err+="Debe especificar el tipo de dato.\n";
else
{
	if(op == 2)
	{
		valor = document.hardw.l2.value;
		if(isNaN(valor) || valor < 2 || valor > 255)
			err+="Debe indicar la longitud del campo entre 2 y 255 caracteres.\n";
	}
}
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}
else 
	return true; 	
}