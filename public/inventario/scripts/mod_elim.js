var sw;
function cerr_menu(){
var me = "";
for(i=0; i<10;i++)
{
	me = "men_"+i;
	if(document.getElementById(me))
	{
		document.getElementById(me).innerHTML = "";
		document.getElementById(me).style.display = "none";
	}
	else
		break;
}
clearTimeout(sw);
}
function hoja_menu(w,x,y){
cerr_menu();
var me = "men_"+w;
document.getElementById(me).style.display = "";
document.getElementById(me).innerHTML = "<div class='con_div'><a href='#' onclick=\"modificar('"+x+"'); cerr_menu();\">Modificar</a></div><div class='con_div'><a href='#' onclick=\"modificar('"+y+"'); cerr_menu();\">Eliminar</a></div>";
sw = setTimeout(cerr_menu, 5000);
}