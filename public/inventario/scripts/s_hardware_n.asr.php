// JavaScript Document
var marc = new Array(0);
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
function iniciar(z){
for(var i = 0; i < z; i++)
	marc[i] = 0;
}
function marcar(x,y,z){
var i = 0;
var mcs = "";
var id_mcs = "";
if(marc[x] == 0)
	marc[x] = 1;
else
	marc[x] = 0;
for(i = 0; i < z; i++)
{
	if(marc[i] == 1)
    {
       	id_mcs += document.hardw.marcas[i].value+","; 
        mcs = mcs+document.hardw.marcas[i].text+","; 
    }
}
mcs = mcs.substring(0,(mcs.length - 1))
id_mcs = id_mcs.substring(0,(id_mcs.length - 1))
document.getElementById("sel_marc").innerHTML = "&nbsp;"+mcs;
document.hardw.marc.value = mcs;
document.hardw.id_marc.value = id_mcs;
}
function busqueda(){
var valor, err;
err="";
valor = document.hardw.t2.value;
if(valor == null || !isNaN(valor) || valor.length < 2 || /^\s+$/.test(valor))
{	
	err+="Debe ingresar el nombre del hardware.\n";
	document.hardw.t2.focus();
}
if(!document.hardw.Contiene[0].checked && !document.hardw.Contiene[1].checked) 
	err += "Se requiere seleccionar jerarquía del hardware. \n";
valor = document.hardw.descripcion.value;
if(valor == null || !isNaN(valor) || valor.length < 10 || /^\s+$/.test(valor))
{	
	err+="Debe realizar la descripción del hardware.\n";
	document.hardw.descripcion.focus();
}
valor = document.hardw.marc.value;
if(valor == null || valor.length < 1 || /^\s+$/.test(valor))
	err+="Debe seleccionar la marca del hardware.\n";
if(err.length > 0)
{
	alert("Verifique los siguientes errores: \n\n"+err+"\n");
	return false; 
}
else
{
	valor = document.hardw.t2.value;
	valor = valor.toLowerCase();
	valor = valor.replace(/[ "]/g, "");
    valor = quit_com(valor);
	valor = document.hardw.tabla.value = valor;
	return true;
}
}