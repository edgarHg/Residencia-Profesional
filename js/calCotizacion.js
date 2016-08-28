// JavaScript Document
// Copyright(c) 2013, eHg @SOFTEKAR

function fncCalcular(caja1, caja2, destino){
caja=document.forms["new_cotizacion"].elements;
var cantidad1 = Number(caja[caja1].value);
var punitario1 = Number(caja[caja2].value);
resultado=cantidad1*punitario1;
if(!isNaN(resultado)){
	caja[destino].value=(cantidad1*punitario1).toFixed(2);
	fncSumarSubtotal();
	fncIva();
	resultadoTotal();
}
}

function fncSumarSubtotal(){
caja=document.forms["new_cotizacion"].elements;
var numero1 = Number(caja['resultado1'].value);
var numero2 = Number(caja['resultado2'].value);
var numero3 = Number(caja['resultado3'].value);
var numero4 = Number(caja['resultado4'].value);
var numero5 = Number(caja['resultado5'].value);
var numero6 = Number(caja['resultado6'].value);
var numero7 = Number(caja['resultado7'].value);
var numero8 = Number(caja['resultado8'].value);
var numero9 = Number(caja['resultado9'].value);
var numero10 = Number(caja['resultado10'].value);
var numero11 = Number(caja['resultado11'].value);
var numero12 = Number(caja['resultado12'].value);
var numero13 = Number(caja['resultado13'].value);
var numero14 = Number(caja['resultado14'].value);

	subtotal=numero1+numero2+numero3+numero4+numero5+numero6+numero7+numero8+numero9+numero10+numero11+numero12+numero13+numero14;
	if(!isNaN(subtotal)){
	caja['subtotal'].value=(numero1+numero2+numero3+numero4+numero5+numero6+numero7+numero8+numero9+numero10+numero11+numero12+numero13+numero14).toFixed(2);	
	}
}
function fncIva(){
	caja=document.forms["new_cotizacion"].elements;
	var numero1 = Number(caja['subtotal'].value);
	iva=numero1*.16;
	if(!isNaN(iva)){
	caja['iva'].value=(numero1*.16).toFixed(2);	
	}
}
function resultadoTotal(){
	caja=document.forms["new_cotizacion"].elements;
	var numero1 = Number(caja['subtotal'].value);
	var numero2 = Number(caja['iva'].value);
	resultadoFinal=subtotal+iva;
			if(!isNaN(resultadoFinal)){
		caja['resultadoFinal'].value=(numero1+numero2).toFixed(2);	
	}
}
function solonumeros(e){
 key = e.keyCode || e.which;
 tecla = String.fromCharCode(key).toLowerCase();
 letras = "1234567890";
 especiales = [8,37,39,46];

 tecla_especial = false
 for(var i in especiales){
     if(key == especiales[i]){
  tecla_especial = true;
  break;
            } 
 }
 
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
     return false;
     }function solonumeros(e){
 key = e.keyCode || e.which;
 tecla = String.fromCharCode(key).toLowerCase();
 letras = "1234567890";
 especiales = [8,37,39,46];

 tecla_especial = false
 for(var i in especiales){
     if(key == especiales[i]){
  tecla_especial = true;
  break;
            } 
 }
 
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
     return false;
     }