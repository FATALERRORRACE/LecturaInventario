var meses=new Array();
meses[1]="Enero"; meses[2]="Febrero"; meses[3]="Marzo"; meses[4]="Abril"; meses[5]="Mayo"; meses[6]="Junio";
meses[7]="Julio"; meses[8]="Agosto"; meses[9]="Septiembre"; meses[10]="Octubre"; meses[11]="Noviembre"; meses[12]="Diciembre";
var fecha = new Date();
var year,year_ac;
year_ac=year=fecha.getFullYear();
var month=fecha.getMonth()+1;
function daysOfMonth(Month,Year){
var num_d=31; 
if(Month==4||Month==6||Month==9||Month==11)
	num_d=30;
else if(Month==2)
	if(((Year%4)==0)&&((Year%100)!=0)||((Year%400)==0)) 
		num_d=29; 
	else 
		num_d=28; 
return num_d;
}
function doHide(id){
layer=document.getElementById(id);
layer.style.display="none";	
layer.innerHTML='';
}
function doShow(id,formname,fieldname){
layer=document.getElementById(id);
layer.style.display="";
layer.innerHTML='';
var innerTMP='';
var dow, day_c;
var date_tem=new Date(year,(month-1),1);
var DayOfWeek=date_tem.getDay();
innerTMP+="<table width=\"100%\" bgcolor='#BBBBBB' cellspacing='1' cellpadding='0'>";
innerTMP+="<tr align='center'><td colspan='6' class='tdc'>";
innerTMP+="<select name='sel_anio' id='sel_anio' class='objeto_3_1' onchange=\"year=this[this.selectedIndex].value;doShow('"+id+"','"+formname+"','"+fieldname+"');\">";
innerTMP+="<option value='"+year+"' selected='selected'>"+year+"</option>";
for(dow=year_ac;dow>(year_ac-20);dow--)
	innerTMP+="<option value='"+dow+"'>"+dow+"</option>";
innerTMP+="</select> ";
innerTMP+=" <select name='sel_mes' id='sel_mes' class='objeto_3_1' onchange=\"month=this[this.selectedIndex].value;doShow('"+id+"','"+formname+"','"+fieldname+"');\">";
innerTMP+="<option value='"+(month)+"' selected='selected'>"+meses[month]+"</option>";
for(dow=1;dow<=12;dow++)
	innerTMP+="<option value='"+dow+"'>"+meses[dow]+"</option>";
innerTMP+="</select></td>";
innerTMP+="<td class='tdc'><a href='#' onClick=\"doHide('"+id+"');\" title='Cerrar el calendario'><strong>X</strong></a></td></tr>";
innerTMP+="<tr align='center' bgcolor='#DDDDDD'>";
var MaxDays=daysOfMonth(month,year);
innerTMP+="<td>Do</td><td>Lu</td><td>Ma</td><td>Mi</td><td>Ju</td><td>Vi</td><td>Sa</td></tr>";
var SDraw=false;
for(day_c=1;day_c<=MaxDays;){
innerTMP+="<tr align='center'>";
	for(dow=0;dow<7;dow++){
	if(dow==DayOfWeek){SDraw=true;}
	innerTMP+="<td class='tdc' bgcolor='#FFFFFF'>";
	if((day_c<=MaxDays) && SDraw){innerTMP+="<a href='#' onClick=\"document.forms['"+formname+"'].elements['"+fieldname+"'].value='"+year+"'+'"+"-"+month+"'+'"+"-"+day_c+"';doHide('"+id+"');\">"+(day_c++)+"</a>";}else if(SDraw){day_c++;innerTMP+="";}
	innerTMP+="</td>";}
innerTMP+="</tr>";
}
innerTMP+="</table>";
layer.innerHTML=innerTMP;
}