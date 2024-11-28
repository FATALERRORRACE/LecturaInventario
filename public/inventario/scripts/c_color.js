// JavaScript Document
function n_color(w,x)
{
var tabla = document.getElementById(x);
var filas = tabla.rows;
for(var i = 1; i < filas.length; i ++)
{
	if(i == w)
		filas[i].style.background="#CCFFCC";
	else
	{
		if(i % 2 == 0)
			//filas[i].className = "bg0";
			filas[i].style.background="#EBEEE5";
		else
			//filas[i].className = "bg1";
			filas[i].style.background="#C6ECFF";
	}
}
}
function modificar(w)
{
	//alert(w);
parent.document.getElementById("portaflot").style.display = "";
document.getElementById("chang_elemt").action = w;
document.getElementById("chang_elemt").submit();

}