// JavaScript Document
var r = t = m = 0;
function sig(a){
r = parseInt(document.form2.reg_s.value);
t = parseInt(document.form2.totalregs.value);
m = parseInt(document.form2.maximos.value);
if(a == "Siguientes" && ((r + 1) * m) < t)
{
	r += 1;
	document.form2.reg_s.value = r;
	document.form2.submit();
}
else if(a == "Anteriores" && r > 0)
{
	r -= 1;
	document.form2.reg_s.value = r;
	document.form2.submit();
}
}