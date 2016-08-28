// JavaScript Document
// Copyright(c) 2013, eHg @SOFTEKAR

jQuery.fn.reset=function(){
	$(this).each(function(){
		this.reset();
	});
}
$.tablesorter.addWidget({
    	id: "indexFirstColumn",
    	format: function(table) {				
    		var contador=0;
			if(table.config.page!=undefined) contador=table.config.page*table.config.size;
    		for(var i=0; i < table.tBodies[0].rows.length; i++) {
    			$("tbody tr:eq(" + (i) + ") td:first",table).html(contador+i+1);
    		}    								
    	}
    });
    
var t={
	entero:"i",
	doble:"d",
	cadena:"s"
};
var r={
	variable:"v",
	elemento:"e",
	sesion:"s",
	funcion:"f"
};
var ha={
	izquierda:"left",
	centro:"center",
	derecha:"right"
};
var va={
	arriba:"top",
	centro:"center",
	abajo:"bottom",
	base:"baseline"
};
var m={
	foco:"focus",
	boton:"button",
	ambos:"both"
};
var d={
	lento:"slow",
	normal:"normal",
	rapido:"fast"
};
var x={
	mensaje:function(elemento, mensaje){
		x.borrar_mensaje(elemento);
		var etiqueta="<label class=\"error\">"+mensaje+"</label>";
		$(etiqueta).insertAfter(u.elemento(elemento));
	},
	borrar_mensaje:function(elemento){
		if(typeof elemento=="string") u.elemento(elemento).next("label").remove()
		else $(this).next("label").remove()
	}
};
var u={
	
	indefinido:function(elemento, valor){
		return elemento==undefined ? valor : elemento;
	},
	vacio:function(elemento, valor){
		return elemento==null ? valor : elemento;
	},
	elemento:function(elemento){
		var e=$("[name="+elemento+"]");
		if(!e.size()) e=$("#"+elemento);
		if(!e.size()) e=$("."+elemento);
		if(e.is(':radio')) e=$("[name="+elemento+"]:radio:checked");
		return e;
	},
	existe:function(elemento){
		return u.elemento(elemento).size();
	},
	valor:function(elemento){
		return u.elemento(elemento).val();
	},
	mensaje:function(elemento, mensaje){
		u.borrar_mensaje(elemento);
		var etiqueta='<label class="error">'+mensaje+'</label>';
		$(etiqueta).insertAfter(u.elemento(elemento));
	},
	borrar_mensaje:function(elemento){
		u.elemento(elemento).next("label").remove()
	},
	evento:function(json){
		u.elemento(json.nombre).off(json.evento).on(json.evento, function(){
			var e=$(this);
			json.accion(e);
		});
	},
	cargar:function(elemento, contenedor, url){
		u.elemento(elemento).click(function(){
			var n=Math.floor((Math.random()*1000)+1);
			u.elemento(contenedor).fadeTo("slow", 0, function(){
				u.elemento(contenedor).load(url+"?id="+n, function(){
					u.elemento(contenedor).fadeTo("slow", 1);
				});
			});
		});
	},
	mostrar:function(contenedor, url){
		var n=Math.floor((Math.random()*1000)+1);
		u.elemento(contenedor).fadeTo("slow", 0, function(){
			u.elemento(contenedor).load(url+"?id="+n, function(){
				u.elemento(contenedor).fadeTo("slow", 1);
			});
		});
	},
	error:function(){
		if($("div#error").size()==0) $("body").prepend('<div id="error"></div>');
	},
	casilla:function(elemento, valor1, valor2, estado, accion){
		this.estado=u.indefinido(estado, false);
		this.accion=u.indefinido(accion, null);
	
		u.elemento(elemento).click(function(){
			if(u.elemento(elemento).is(":checked")){
				u.elemento(elemento).attr("checked", "checked");
				u.elemento(elemento).val(valor1);
			}
			else{
				u.elemento(elemento).removeAttr("checked");
				u.elemento(elemento).val(valor2);
			}
			if(accion!=null) accion();
		});
		this.marcar=function(){
			u.elemento(elemento).attr("checked", "checked");
			u.elemento(elemento).val(valor1);
		}
		this.desmarcar=function(){
			u.elemento(elemento).removeAttr("checked");
			u.elemento(elemento).val(valor2);
		}
		var e=this;
		(this.estado) ? e.marcar() : e.desmarcar();
	},
	radio:function(elemento, valor, accion){
		$("[name="+elemento+"]:radio").click(function(){
			accion();
		});
	},
	mascara:function(elemento, mascara, separador){
		u.elemento(elemento).mask(mascara, {placeholder:separador});
	},	
	calendario:function(json){
	this.e=u.indefinido(json.elemento, "");
		var j=this;
		u.elemento(j.e).calendarioDW();
	}
};
var f={
	elemento:function(json){
		this.nombre=u.indefinido(json.nombre, "");
		this.tipo=u.indefinido(json.tipo, "letras");
		this.requerido=u.indefinido(json.requerido, true);
	},
	parametro:function(json){
		this.tipo=u.indefinido(json.tipo, t.cadena);
		this.valor=u.indefinido(json.valor, "");
		this.referencia=u.indefinido(json.referencia, r.variable);
		this.encriptar=u.indefinido(json.encriptar, false);
		this.nombre=u.indefinido(json.nombre, (this.referencia=="v" || this.referencia=="f") ? "valor[]" : json.valor);
		this.metodo=u.indefinido(json.metodo, false);
		
		this.obtenerValor=function(){
			var valor;
			if(this.referencia=="v")
				valor=(this.encriptar) ? u.encriptar(this.valor) : this.valor;
			else if(this.referencia=="e")
				valor=(this.encriptar) ? u.encriptar(u.valor(this.valor)) : u.valor(this.valor);
			else if(this.referencia=="s")
				valor=(this.encriptar) ? u.encriptar(s.consultar(this.valor)) : s.consultar(this.valor);
			else if(this.referencia=="f")
				valor=(this.encriptar) ? u.encriptar(this.valor[this.metodo]()) : this.valor[this.metodo]();
						
			return valor;
		}
	},
	columna:function(json){
		this.titulo=u.indefinido(json.titulo, "");
		this.valor=u.indefinido(json.valor, "");
		this.pie=u.indefinido(json.pie, "");
		this.ancho=u.indefinido(json.ancho, "100");
		this.ha=u.indefinido(json.ha, ha.izquierda);
		this.va=u.indefinido(json.va, va.centro);
		this.clasificar=u.indefinido(json.clasificar, true);
	},
	validar:function(json){
		var ejecutar=true;
		var letras = /^[a-zA-Z\u00e1\u00c1\u00e9\u00c9\u00ed\u00cd\u00f3\u00d3\u00fa\u00da\u00f1\u00d1\s]*$/;
		var texto = /^[a-zA-Z0-9\u00e1\u00c1\u00e9\u00c9\u00ed\u00cd\u00f3\u00d3\u00fa\u00da\u00f1\u00d1\-\.\,\:\´\(\)\""\s]*$/;
		var correo = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/;
		var alfanumerico = /^[a-zA-Z0-9\-\/-/-\.\u00e1\u00c1\u00e9\u00c9\u00ed\u00cd\u00f3\u00d3\u00fa\u00da\u00f1\u00d1\s]*$/;
		var numero = /^[0-9]*$/;
		var combo = /^[0-9]*$/;
		var cupos = /^[0-9]*$/;
		var calendario = /^[0-9\-\/]*$/;

		for(var i=0; i<json.elementos.length; i++ ){
			var elemento=json.elementos[i].nombre;
			var tipo=json.elementos[i].tipo;
			var requerido=json.elementos[i].requerido;
			var valor_elemento=u.valor(elemento);
			
			if(!u.existe(elemento)){
				ejecutar=false;
				alert("Elemento no encontrado: "+elemento)
			}
			else{
				if(tipo=="letras"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!letras.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Dato incorrecto");
					}
				}
				else if(tipo=="texto"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!texto.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Dato incorrecto");
					}
				}
				else if(tipo=="correo"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!correo.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Dato incorrecto");
					}
				}
				else if(tipo=="alfanumerico"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!alfanumerico.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Dato incorrecto");
					}
				}
				else if(tipo=="combo"){
					if(requerido==true && valor_elemento==0){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!combo.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Dato incorrecto");
					}
				}
					else if(tipo=="cupos"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}else if(requerido==true && valor_elemento<=4){
						ejecutar=false;
						x.mensaje(elemento,"Minimo 5");
					} 
					else if(!cupos.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Teclee solo numeros");
					}
				}
				else if(tipo=="numero"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					else if(!numero.test(valor_elemento)){
						ejecutar=false;
						x.mensaje(elemento,"Teclee solo numeros");
					}
				}
				else if(tipo=="calendario"){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
					
				}
				else if(tipo==false){
					if(requerido==true && valor_elemento==""){
						ejecutar=false;
						x.mensaje(elemento,"Dato requerido");
					}
				}
				u.elemento(elemento).off("change", x.borrar_mensaje).on("change", x.borrar_mensaje);
			}
		}
		if(ejecutar) json.accion();
	},combo_simple:function(json){
		u.elemento(json.elemento).children("[dinamico]").remove();
		
		if(json.valores!=undefined){
			for(var i=0; i<json.valores.length; i++)
			{
				u.elemento(json.elemento).append('<option dinamico value="'+json.valores[i]+'">'+json.descripciones[i]+'</option>');
			}
		}
	},
	combo:function(json){
		this.e=u.indefinido(json.elemento, "");
		this.sentencia=u.indefinido(json.sentencia, "");
		this.valor=u.indefinido(json.valor, "");
		this.descripcion=u.indefinido(json.descripcion, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		this.cambio=u.indefinido(json.cambio, null);
		
		var j=this;
		this.ejecutar=function(){
			var tipos=";&tipos=";
			var valores="";
			var datos="";
			if(j.parametros!=null){
				for(var i=0; i<j.parametros.length; i++){
					tipos+=j.parametros[i].tipo;
					valores+="&valor[]=";
					if(j.parametros[i].referencia=="v")
						valores+=(j.parametros[i].encriptar) ? f.encriptar(j.parametros[i].valor) : j.parametros[i].valor;
					else if(j.parametros[i].referencia=="e")
						valores+=(j.parametros[i].encriptar) ? f.encriptar(u.valor(j.parametros[i].valor)) : u.valor(j.parametros[i].valor);
					else if(j.parametros[i].referencia=="s")
						valores+=(j.parametros[i].encriptar) ? f.encriptar(s.consultar(j.parametros[i].valor)) : s.consultar(j.parametros[i].valor);
				}
				datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
			}
			else datos="peticion=mysql&sentencia="+j.sentencia;
			
			$.ajax({
				url:"php/peticiones.php",
				data:datos,
				async:false,
				type:"POST",
				cache:false,
				dataType:"json",
				timeout:30000,
				error:function(jqXHR, status, error){
					$("#error").html(jqXHR.responseText);
				},
				success:function(datos){
					if(datos.resultados!=0){
						f.combo_simple({
							elemento:j.e,
							valores:datos.columnas[j.valor],
							descripciones:datos.columnas[j.descripcion]
						});
					}
					else u.elemento(j.e).children("[dinamico]").remove();
					if(j.accion!=null) j.accion(datos);
				},
				complete:function(jqXHR, status){
					if(status=="success"){
						$("#error").html("");
					}
				}
			});
		}
		if(this.cambio!=null){
			u.evento({
				nombre:j.e,
				evento:"change",
				accion:function(e){
					json.cambio();
				}
			});
		}
		if(this.ejecuta) this.ejecutar();
	}
	,
	consulta:function(json){
		this.sentencia=u.indefinido(json.sentencia, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.mensajes=u.indefinido(json.mensajes, true);
		this.correcto=u.indefinido(json.correcto, "Los datos han sido guardados");
		this.error=u.indefinido(json.error, "Error de envio");
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		
		var j=this;
		this.ejecutar=function(){
			var tipos=";&tipos=";
			var valores="";
			var datos="";
			if(j.parametros!=null){
				for(var i=0; i<j.parametros.length; i++){
					tipos+=j.parametros[i].tipo;
					valores+="&valor[]="+j.parametros[i].obtenerValor();
				}
				datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
			}
			else datos="peticion=mysql&sentencia="+j.sentencia;
		
			$.ajax({
				url:"php/peticiones.php",
				data:datos,
				async:false,
				type:"POST",
				cache:false,
				dataType:"json",
				timeout:30000,
				beforeSend:function(){
					if(j.mensajes) $(".mensajes").fadeIn("fast").html("<img src=\"img/cargando.gif\"/>");
				},
				error:function(jqXHR, status, error){
					$("#error").html(jqXHR.responseText);
				},
				success:function(datos){
					if(j.accion!=null) j.accion(datos);
				},
				complete:function(jqXHR, status){
					if(j.mensajes){
						if(status=="success"){
							$("#error").html("");
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
							});
						}
						else{
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
							});
						}
					}
				}
			});
		}
		
		if(this.ejecuta) this.ejecutar();
	},
	consultaLista:function(json){
		this.sentencia=u.indefinido(json.sentencia, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.mensajes=u.indefinido(json.mensajes, true);
		this.correcto=u.indefinido(json.correcto, "Los datos han sido guardados");
		this.error=u.indefinido(json.error, "Error de envio");
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		this.accionSecundaria=u.indefinido(json.accionSecundaria, null);
		
		var j=this;
		this.ejecutar=function(){
			var n=1;
			for(var i=0; i<j.parametros.length; i++){
				if(j.parametros[i].referencia=="e"){
					n=u.existe(j.parametros[i].valor);
					break;
				}
			}
			
			var estado="";
			if(j.mensajes) $(".mensajes").fadeIn("fast").html('<img src="img/cargando.gif"/>');
			for(var i=0; i<n; i++){
				var tipos=";&tipos=";
				var valores="";
				var datos="";
				if(j.parametros!=null){
					for(var k=0; k<j.parametros.length; k++){
						tipos+=j.parametros[k].tipo;
						valores+="&valor[]=";
						if(j.parametros[k].referencia=="v")
							valores+=(j.parametros[k].encriptar) ? f.encriptar(j.parametros[k].valor) : j.parametros[k].valor;
						else if(j.parametros[k].referencia=="e")
							valores+=(j.parametros[k].encriptar) ? f.encriptar(u.elemento(j.parametros[k].valor)[i].value) : u.elemento(j.parametros[k].valor)[i].value;
						else if(j.parametros[k].referencia=="s")
							valores+=(j.parametros[k].encriptar) ? f.encriptar(s.consultar(j.parametros[k].valor)) : s.consultar(j.parametros[k].valor);
					}
					datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
				}
				else datos="peticion=mysql&sentencia="+j.sentencia;
				
				$.ajax({
					url:"php/peticiones.php",
					data:datos,
					async:false,
					type:"POST",
					cache:false,
					dataType:"json",
					timeout:30000,
					error:function(jqXHR, status, error){
						$("#error").html(jqXHR.responseText);
					},
					success:function(datos){
						if(j.accionSecundaria!=null) j.accionSecundaria(datos);
					},
					complete:function(jqXHR, status){
						estado=status;
					}
				});
				if(j.mensajes){
					if(estado!="success"){
						$(".mensajes").delay(3000).fadeOut("fast",function(){
							$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
						});
						break;
					}
				}
			}
			if(j.mensajes){
				if(estado=="success"){
					$("#error").html("");
					$(".mensajes").delay(3000).fadeOut("fast",function(){
						$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
					});
				}
			}
			if(j.accion!=null) j.accion();
		}
		
		if(this.ejecuta) this.ejecutar();
	},
	consultaListaSeleccion:function(json){
		this.seleccion=u.indefinido(json.seleccion, null);
		this.sentencia=u.indefinido(json.sentencia, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.mensajes=u.indefinido(json.mensajes, true);
		this.correcto=u.indefinido(json.correcto, "Los datos han sido guardados");
		this.error=u.indefinido(json.error, "Error de envio");
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		this.accionSecundaria=u.indefinido(json.accionSecundaria, null);
		
		var j=this;
		this.ejecutar=function(){
			var n=1;
			for(var i=0; i<j.parametros.length; i++){
				if(j.parametros[i].referencia=="e"){
					n=u.existe(j.parametros[i].valor);
					break;
				}
			}
			
			var estado="";
			if(j.mensajes) $(".mensajes").fadeIn("fast").html("<img src=\"img/cargando.gif\"/>");
			for(var i=0; i<n; i++){
				if(j.seleccion!=null && u.elemento(j.seleccion)[i].is(":checked")){
					var tipos=";&tipos=";
					var valores="";
					var datos="";
					if(j.parametros!=null){
						for(var k=0; k<j.parametros.length; k++){
							tipos+=j.parametros[k].tipo;
							valores+="&valor[]="+j.parametros[i].obtenerValor();
						}
						datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
					}
					else datos="peticion=mysql&sentencia="+j.sentencia;
					
					$.ajax({
						url:"php/peticiones.php",
						data:datos,
						async:false,
						type:"POST",
						cache:false,
						dataType:"json",
						timeout:30000,
						error:function(jqXHR, status, error){
							$("#error").html(jqXHR.responseText);
						},
						success:function(datos){
							if(j.accionSecundaria!=null) j.accionSecundaria(datos);
						},
						complete:function(jqXHR, status){
							estado=status;
						}
					});
					if(j.mensajes){
						if(estado!="success"){
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
							});
							break;
						}
					}
				}
			}
			if(j.mensajes){
				if(estado=="success"){
					$("#error").html("");
					$(".mensajes").delay(3000).fadeOut("fast",function(){
						$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
					});
				}
			}
			if(j.accion!=null) j.accion();
		}
		
		if(this.ejecuta) this.ejecutar();
	},
	tabla:function(json){
		this.e=u.indefinido(json.elemento, "");
		this.sentencia=u.indefinido(json.sentencia, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.columnas=u.indefinido(json.columnas, null);
		this.id=u.indefinido(json.id, "");
		this.contador=u.indefinido(json.contador, false);
		this.bordes=u.indefinido(json.bordes, 0);
		this.pie=u.indefinido(json.pie, false);
		this.clasificador=u.indefinido(json.clasificador, false);
		this.claseClasificador=u.indefinido(json.claseClasificador, "tablesorter");
		this.paginador=u.indefinido(json.paginador, false);
		this.clasePaginador=u.indefinido(json.clasePaginador, "pager");
		this.ver=u.indefinido(json.ver, ["","",""]);
		this.mensajes=u.indefinido(json.mensajes, false);
		this.correcto=u.indefinido(json.correcto, "Lista carganda correctamente");
		this.error=u.indefinido(json.error, "Error de envio");
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		
		var j=this;
		var habilitado={};
		
		var tabla="<table border=\""+j.bordes+"\" class=\""+j.claseClasificador+"\"><thead><tr>";
		if(j.contador) tabla+="<th width=\"10px\" class=\"{sorter:false}\"></th>";
		for(var i=0; i<j.columnas.length; i++){
			tabla+="<th width=\""+j.columnas[i].ancho+"px\">"+j.columnas[i].titulo+"</th>";
			if(!j.columnas[i].clasificar) habilitado[i+j.contador]={sorter:j.columnas[i].clasificar}
		}
		tabla+="</tr></thead><tbody></tbody><tfoot>";
		
		if(j.paginador){
			tabla+="<tr><th colspan=\""+(j.columnas.length+j.contador)+"\" align=\""+ha.centro+"\">";
			tabla+="<div id=\""+j.clasePaginador+"\" class=\"pager\">";
			tabla+="<img class=\"first\" title=\"Ir a la primera pagina\"/><img class=\"prev\" title=\"Ir a la pagina anterior\"/>";
			tabla+="<select class=\"page\" title=\"Ir a la pagina\"></select>";
			tabla+="<input type=\"text\" class=\"pagedisplay\" readonly=\"readonly\" title=\"Pagina actual\"/>";
			tabla+="<select class=\"pagesize\" title=\"Numero de registros por pagina\">";
			$.each(j.ver, function(key, value){
				tabla+="<option value=\""+value+"\">"+value+"</option>"
			});
			tabla+="</select>";
			tabla+="<img class=\"next\" title=\"Ir a la pagina siguiente\"/><img class=\"last\" title=\"Ir a la primera pagina\"/>";
			tabla+="</div></th></tr>";
		}
		
		if(j.pie){
			tabla+="<tr>";
			for(var i=0; i<j.columnas.length; i++){
				tabla+="<th>"+j.columnas[i].pie+"</th>";
			}
			tabla+="</tr>";
			tabla+="</tfoot></table>";
		}
		
		u.elemento(j.e).html(tabla);
		
		this.ejecutar=function(){
			var tipos=";&tipos=";
			var valores="";
			var datos="";
			if(j.parametros!=null){
				for(var i=0; i<j.parametros.length; i++){
					tipos+=j.parametros[i].tipo;
					valores+="&valor[]="+j.parametros[i].obtenerValor();
				}
				datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
			}
			else datos="peticion=mysql&sentencia="+j.sentencia;
		
			$.ajax({
				url:"php/peticiones.php",
				data:datos,
				async:false,
				type:"POST",
				cache:false,
				dataType:"json",
				timeout:30000,
				beforeSend:function(){
					if(j.mensajes) $(".mensajes").fadeIn("fast").html("<img src=\"img/cargando.gif\"/>");
				},
				error:function(jqXHR, status, error){
					$("#error").html(jqXHR.responseText);
				},
				success:function(datos){
					var contenido_cuerpo
					if(datos.resultados!=0){
						for(var i=0; i<datos.filas.length; i++){
							contenido_cuerpo+=(i%2) ? "<tr id=\""+u.indefinido(datos.filas[i][j.id], "")+"\">" : "<tr id=\""+u.indefinido(datos.filas[i][j.id], "")+"\" class=\"odd\">";
							if(j.contador) contenido_cuerpo+="<td align=\""+ha.centro+"\">"+(i+1)+"</td>";
							for(var k=0; k<j.columnas.length; k++){
								var c=j.columnas[k].valor;
								$.each(datos.filas[i],function(key, value){
									var patron = new RegExp("#"+key+"#","gi");
									c = c.replace(patron, u.vacio(value,""));
								});
								contenido_cuerpo+="<td align=\""+j.columnas[k].ha+"\" valign=\""+j.columnas[k].va+"\">"+c+"</td>"
							}
							contenido_cuerpo+="</tr>";
						}
					}
					
					u.elemento(j.e).children("table").children("tbody").html(contenido_cuerpo);
					u.elemento(j.e).children("table").trigger("update");
					if(datos.resultados!=0 && j.paginador) u.elemento(j.e).children("table").tablesorterPager({container: u.elemento("pager"), positionFixed:false, size:u.valor("pagesize")});
					
					if(j.accion!=null) j.accion(datos);
				},
				complete:function(jqXHR, status){
					if(j.mensajes){
						if(status=="success"){
							$("#error").html("");
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
							});
						}
						else{
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
							});
						}
					}
				}
			});
		}
		var widget = new Array();
		widget[0]="zebra";
		if(j.contador) widget[1]="indexFirstColumn";
		if(this.clasificador)
			u.elemento(j.e).children("table").tablesorter({
				headers:habilitado,
				widgets:widget
			});
		
		if(this.ejecuta) this.ejecutar();
	},
	enviar:function(json){
		this.formulario=u.indefinido(json.formulario, "");
		this.parametros=u.indefinido(json.parametros, false);
		this.peticion=u.indefinido(json.peticion, false);
		this.url=u.indefinido(json.url, "php/peticiones.php");
		this.asincrona=u.indefinido(json.asincrona, "false");
		this.metodo=u.indefinido(json.metodo, "post");
		this.cache=u.indefinido(json.cache, "false");
		this.tipo=u.indefinido(json.tipo, "json");
		this.mensajes=u.indefinido(json.mensajes, true);
		this.correcto=u.indefinido(json.correcto, "Los datos han sido enviados");
		this.error=u.indefinido(json.error, "Error de envio");
		this.accion=u.indefinido(json.accion, null);
		this.ejecuta=u.indefinido(json.ejecuta, true);
		
		j=this;
		this.ejecutar=function(){
			var valores="";
			var datos=(j.peticion) ? "peticion="+j.peticion : "";
			if(j.parametros){
				for(var i=0; i<j.parametros.length; i++){
					valores+="&valor[]="+j.parametros[i].obtenerValor();
				}
				datos+="&"+u.elemento(j.formulario).serialize()+valores;
			}
			else datos+="&"+u.elemento(j.formulario).serialize();
			
			$.ajax({
				url:j.url,
				data:datos,
				async:j.asincrona,
				type:j.metodo,
				cache:j.cache,
				dataType:j.tipo,
				timeout:30000,
				beforeSend:function(){
					if(j.mensajes) $(".mensajes").fadeIn("fast").html("<img src=\"img/cargando.gif\"/>");
				},
				error:function(jqXHR, status, error){
					$("#error").html(jqXHR.responseText);
				},
				success:function(datos){
					if(j.accion!=null) j.accion(datos);
				},
				complete:function(jqXHR, status){
					if(j.mensajes){
						if(status=="success"){
							$("#error").html("");
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
							});
						}
						else{
							$(".mensajes").delay(3000).fadeOut("fast",function(){
								$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
							});
						}
					}
				}
			});
		}
		
		if(this.ejecuta) this.ejecutar();
	},
	subir:function(json){
		this.elemento=u.indefinido(json.elemento, false);
		this.multiple=u.indefinido(json.multiple, false);
		this.tipos=u.indefinido(json.tipos, false);
		this.limite=u.indefinido(json.limite, false);
		this.tamano=u.indefinido(json.tamano, false);
		this.lista=u.indefinido(json.lista, false);
		this.miniatura=u.indefinido(json.miniatura, false);
		this.remover=u.indefinido(json.remover, true);
		this.nombreArchivo=u.indefinido(json.nombreArchivo, false);
		this.nombreArchivoTemp=new Array();
		this.nombreCarpeta=u.indefinido(json.nombreCarpeta, false);
		this.existe=u.indefinido(json.existe, false);
		this.mensajes=u.indefinido(json.mensajes, false);
		this.correcto=u.indefinido(json.correcto, "Los datos han sido enviados");
		this.error=u.indefinido(json.error, "Error de envio");
		this.accion=u.indefinido(json.accion, null);
		this.ejecuta=u.indefinido(json.ejecuta, true);
		
		if(u.elemento(this.elemento).is(":file") && window.File && window.FileList && window.FileReader && window.FormData){
			this.ejecutar=function(){
				$.each(u.elemento("archivo_subir"), function(key, value){ 
					formdata = new FormData(); var id=$(this).attr("id");
					if(id>n){
						formdata.append("archivo", archivos[id]);
						formdata.append("peticion", "subir_archivo");
						formdata.append("nombreArchivo", j.nombreArchivoTemp[id]);
						formdata.append("nombreCarpeta", j.nombreCarpeta);
						$.ajax({
							url:"php/peticiones.php",
							data:formdata,
							async:false,
							type:"POST",
							cache:false,
							dataType:"json",
							processData:false,
							contentType:false,
							beforeSend:function(){
								if(j.mensajes) $(".mensajes").fadeIn("fast").html("<img src=\"img/cargando.gif\"/>");
							},
							error:function(jqXHR, status, error){
								$("#error").html(jqXHR.responseText);
							},
							success:function(datos){
								u.elemento(id).children(".estado_subir").text("Completado...");
								if(j.remover) u.elemento(id).remove();
							},
							complete:function(jqXHR, status){
								if(j.mensajes){
									if(status=="success"){
										$("#error").html("");
										$(".mensajes").delay(3000).fadeOut("fast",function(){
											$(this).fadeIn("slow").html("<label class=\"correcto\">"+j.correcto+"</label>").delay(3000).fadeOut("slow");
										});
									}
									else{
										$(".mensajes").delay(3000).fadeOut("fast",function(){
											$(this).fadeIn("fast").html("<label class=\"error\">"+j.error+"</label>");
										});
									}
								}
							}
						});
						n=id;
					}
				});
			}
			
			var j=this; var archivos=new Array(); var contador=0; var n=-1;
			
			$("<div name=lista_archivos_subir class=lista_archivos_subir></div>").insertAfter(u.elemento(j.elemento));
			if(j.multiple) u.elemento(j.elemento).attr("multiple","");
			if(j.tipos) u.elemento(j.elemento).attr("accept",j.tipos);
			if(!j.lista  && !j.multiple) u.elemento("lista_archivos_subir").hide();
			
			u.elemento(j.elemento).change(function(){
				var archivo=this.files;
				for(var i=0; i<archivo.length; i++){
					var c=0;
					if(j.tipos){
						var t=j.tipos.split(",");
						/** Verificar tipo de archivo */
						for(var k=0; k<t.length; k++){
							if(archivo[i].type==t[k]){
								c=0;
								break;
							}
							else c=1;
						}
					}
					/** ------------------------- */
					/** Verificar el limite de archivos para subir */
					if(j.limite && archivos.length>=j.limite+contador){
						c=2;
					}
					/** ----------------------------- */
					/** Calcular el tamaño */
					var tamano=archivo[i].size;
					if(tamano<=1024) tamano=tamano+" B";
					if(tamano>=1024 && tamano<=1048576) tamano=(tamano/1024).toFixed(2)+" Kb";
					if(tamano>=1048576 && tamano<=1073741824) tamano=(tamano/1024/1024).toFixed(2)+" Mb";
					/** ------------------ */
					/** Varificar el tamaño del archivo */
					if(j.tamano){
						var x=j.tamano.split(" "); var y=tamano.split(" ");
						if(parseFloat(y[0])>parseFloat(x[0]) && y[1]==x[1]) c=3;
					}
					/** ------------------------------- */
					j.nombreArchivoTemp[archivos.length]=(j.nombreArchivo && !j.lista && !j.multiple) ? j.nombreArchivo+archivo[i].name.substring(archivo[i].name.lastIndexOf(".")) : archivo[i].name;
					if(j.existe){
						$.ajax({
							url:"php/peticiones.php",
							data:"peticion=buscar_archivo&nombreCarpeta="+j.nombreCarpeta+"&nombreArchivo="+j.nombreArchivoTemp,
							async:false,
							type:"POST",
							cache:false,
							dataType:"json",
							timeout:30000,
							error:function(jqXHR, status, error){
								$("#error").html(jqXHR.responseText);
							},
							success:function(datos){
								if(datos.existe)
									if(!confirm("El archivo ya existe, ¿Sustituir?"))
										c=4;
							}
						});
					}
					if(c==0){
						if(!j.lista && !j.multiple){
							u.elemento("archivo_subir").remove();
						}
						/** Mostrar lista */
						var html="";
						html+="<div name=\"archivo_subir\" id=\""+archivos.length+"\" class=\"archivo_subir\">";
						html+="<span class=\"nombre_archivo_subir\">"+archivo[i].name+"</span><span class=\"tamano_archivo_subir\">("+tamano+")</span>";
						html+="<span class=\"estado_subir\">En cola...</span>";
						html+="<img src=\"img/cancelar.png\" name=\"cancelar_subir\" class=\"cancelar_subir\"/>"
						html+="<div class=\"clear\"></div>";
						html+="</div>";
						u.elemento("lista_archivos_subir").append(html);
						/** ------------ */
						/** Miniaturas */
						if(j.lista && j.miniatura){
							if(archivo[i].type.match(/image.*/)){
								leer=new FileReader();
								leer.onloadend=(function(a){
									return function(e){
										u.elemento(a).prepend("<img src=\""+e.target.result+"\"/ class=\"miniatura_subir\">");
									}; 
								})(archivos.length);
								leer.readAsDataURL(a = archivo[i]);
							}
							else if(archivo[i].type.match(/application\/pdf/)){
								u.elemento(archivos.length).prepend("<img src=\"img/iconos/pdf.png\"/ class=\"miniatura_subir\">");
							}
							else if(archivo[i].name.match(/[^.docx]*\.docx/) || archivo[i].name.match(/[^.doc]*\.doc/)){
								u.elemento(archivos.length).prepend("<img src=\"img/iconos/docx.png\"/ class=\"miniatura_subir\">");
							}
							else if(archivo[i].name.match(/[^.xlsx]*\.xlsx/) || archivo[i].name.match(/[^.xls]*\.xls/)){
								u.elemento(archivos.length).prepend("<img src=\"img/iconos/xlsx.png\"/ class=\"miniatura_subir\">");
							}
							else if(archivo[i].name.match(/[^.pptx]*\.pptx/) || archivo[i].name.match(/[^.ppt]*\.ppt/)){
								u.elemento(archivos.length).prepend("<img src=\"img/iconos/pptx.png\"/ class=\"miniatura_subir\">");
							}
							else if(archivo[i].name.match(/[^.ppsx]*\.ppsx/) || archivo[i].name.match(/[^.pps]*\.pps/)){
								u.elemento(archivos.length).prepend("<img src=\"img/iconos/ppsx.png\"/ class=\"miniatura_subir\">");
							}
						}
						/** --------- */
						archivos[archivos.length]=archivo[i];
					}
					else{
						switch(c){
							case 1:
								alert("Tipo de archivo invalido");
								break;
							case 2:
								alert("A llegado al limite de archivos para subir");
								break;
							case 3:
								alert("El archivo supera el tamaño establecido");
								break;
						}
					}
				}
				
				u.evento({
					elemento:"cancelar_subir",
					evento:"click",
					accion:function(e){
						e.parent().remove();
						contador++;
					}
				});
				
				if(j.ejecuta) j.ejecutar();
			});
		}
		else alert("El navegador no es compatible con el gestor para subir archivos");
		/** Tipos de archivos */
		/**  
			-- image/*	-- image/jpeg,image/png
			-- audio/*
			-- video/*
			-- application/pdf
			-- application/vnd.ms-excel	//.xls
			-- application/vnd.openxmlformats-officedocument.spreadsheetml.sheet	//.xlsx
			-- application/vnd.ms-powerpoint	//.pps
			-- application/vnd.openxmlformats-officedocument.presentationml.slideshow	//.ppsx
			-- application/vnd.openxmlformats-officedocument.presentationml.presentation	//.pptx
			-- application/msword	//.doc
			-- application/vnd.openxmlformats-officedocument.wordprocessingml.document	//.docx
		*/
		/** Mas tipos */
		/** http://www.webmaster-toolkit.com/mime-types.shtml */
	},
	autocompletar:function(json){
		this.e=u.indefinido(json.elemento, "");
		this.sentencia=u.indefinido(json.sentencia, "");
		this.parametros=u.indefinido(json.parametros, null);
		this.valor=u.indefinido(json.valor, "");
		this.descripcion=u.indefinido(json.descripcion, this.valor);
		this.categoria=u.indefinido(json.categoria, false);
		this.numCaracteres=u.indefinido(json.numCaracteres, 1);
		this.ejecuta=u.indefinido(json.ejecuta, true);
		this.accion=u.indefinido(json.accion, null);
		this.cambio=u.indefinido(json.cambio, null);
		
		var j=this; var d;
		this.ejecutar=function(){
			var tipos=";&tipos=";
			var valores="";
			var datos="";
			if(j.parametros!=null){
				for(var i=0; i<j.parametros.length; i++){
					tipos+=j.parametros[i].tipo;
					valores+="&valor[]=";
					if(j.parametros[i].referencia=="v")
						valores+=(j.parametros[i].encriptar) ? u.encriptar(j.parametros[i].valor) : j.parametros[i].valor;
					else if(j.parametros[i].referencia=="e")
						valores+=(j.parametros[i].encriptar) ? u.encriptar(u.valor(j.parametros[i].valor)) : u.valor(j.parametros[i].valor);
					else if(j.parametros[i].referencia=="s")
						valores+=(j.parametros[i].encriptar) ? u.encriptar(s.consultar(j.parametros[i].valor)) : s.consultar(j.parametros[i].valor);
				}
				datos="peticion=mysqli_stmt&sentencia="+j.sentencia+tipos+valores;
			}
			else datos="peticion=mysql&sentencia="+j.sentencia;
			
			$.ajax({
				url:"php/peticiones.php",
				data:datos,
				async:false,
				type:"POST",
				cache:false,
				dataType:"json",
				timeout:30000,
				error:function(jqXHR, status, error){
					$("#error").html(jqXHR.responseText);
				},
				success:function(datos){
					if(datos.resultados!=0){
						//if(j.categoria) availableTags.category=item[j.categoria]
						u.elemento(j.e).autocomplete({
							minLength: j.numCaracteres,
							source:function(request, response){
								response($.ui.autocomplete.filter($.map(datos.filas, function(item){
									var availableTags={
										label:item[j.descripcion],
										value:item[j.valor]
									}
									$.each(item, function(key, value){
										availableTags[key]=value;
									});
									return availableTags;
								}), request.term));
							},
							select:function(event, ui){
								if(j.cambio!=null) j.cambio(ui.item);
							}
						});
					}
					if(j.accion!=null) j.accion(datos);
				},
				complete:function(jqXHR, status){
					if(status=="success"){
						$("#error").html("");
					}
				}
			});
		}
		if(this.ejecuta) this.ejecutar();
	},
	reporte:function(json){
		this.parametros=u.indefinido(json.parametros, null);
		this.numero=u.indefinido(json.numero, "");
		this.url=u.indefinido(json.url, "php/reportes.php");
		this.ventana=u.indefinido(json.ventana, 0);
		this.ancho=u.indefinido(json.ancho, screen.width/2);
		this.ancho=(this.ancho<=screen.width) ? this.ancho : screen.width/2;
		this.alto=u.indefinido(json.alto, screen.height-200);
		this.alto=(this.alto<=(screen.height-200)) ? this.alto : screen.height-200;
		this.izquierda=u.indefinido(json.izquierda, (screen.width-this.ancho)/2);
		this.arriba=u.indefinido(json.arriba, (screen.height-this.alto)/4);
		this.ejecuta=u.indefinido(json.ejecuta, false);
		
		var j=this;
		this.ejecutar=function(){
			var valores="";
			var datos="";
			if(j.parametros!=null){
				for(var i=0; i<j.parametros.length; i++){
					valores+="&"+j.parametros[i].nombre+"="+j.parametros[i].obtenerValor();
				}
				datos="reporte="+j.numero+valores;
			}
			else datos="reporte="+j.numero;
			
			if(j.ventana==0){
				if($("iframe#descarga").size()==0) $("body").append("<iframe  id=\"descarga\" src=\"\" style=\"display:none; visibility:hidden;\"></iframe >");
				$("iframe#descarga").attr("src" , j.url+"?"+datos);
				setTimeout(function(){ $("iframe#descarga").remove(); }, 5000);
			}
			else if(j.ventana==1) window.open(j.url+"?"+datos);
			else if(j.ventana==2) window.open(j.url+"?"+datos, "reporte", "width="+j.ancho+", height="+j.alto+", left="+j.izquierda+", top="+j.arriba+", scrollbars=NO, resizable=YES");
		}
		if(this.ejecuta) this.ejecutar();
	}
};
var s={
	crear:function(json){
		this.nombre=u.indefinido(json.nombre, "id");
		this.valor=u.indefinido(json.valor, null);
		this.recordar=u.indefinido(json.recordar, false);
		this.tiempo=u.indefinido(json.tiempo, 365);
		
		fecha=new Date();
		fecha.setDate(fecha.getDate() + this.tiempo);
		var sesion=this.nombre+"="+this.valor;
		var r=this.nombre+"_recordar="+this.recordar
		var t=this.nombre+"_tiempo="+this.tiempo
		if(this.recordar){
			sesion+="; expires="+fecha.toUTCString();
			r+="; expires="+fecha.toUTCString();
			t+="; expires="+fecha.toUTCString();
		}
		document.cookie=sesion;
		document.cookie=r;
		document.cookie=t;
	},
	buscar:function(nombre){
		v=false; 
		c=document.cookie.split(";");
		for(var i=0; i<c.length; i++){
			nv=c[i].split("=");
			if(nv[0].replace(/\s/g,"")==nombre){
				v=true;
				break;
			}
		}
		return v;
	},
	consultar:function(nombre){
		v=null; 
		c=document.cookie.split(";");
		for(var i=0; i<c.length; i++){
			nv=c[i].split("=");
			if(nv[0].replace(/\s/g,"")==nombre){
				v=nv[1];
				break;
			}
		}
		return v;
	},
	modificar:function(json){
		s.crear({nombre:json.nombre, valor:json.valor, recordar:s.consultar(json.nombre+"_recordar"), tiempo:s.consultar(json.nombre+"_tiempo")});
	},
	eliminar:function(n){
		if(n!=undefined){
			s.crear({nombre:n, recordar:true, tiempo:-1});
		}
		else{
			c=document.cookie.split(";");
			for(var i=0; i<c.length; i++){
				nv=c[i].split("=");
				var n=nv[0].replace(/\s/g,"")
				s.crear({nombre:n, recordar:true, tiempo:-1});
				s.crear({nombre:n+"_recordar", recordar:true, tiempo:-1});
				s.crear({nombre:n+"_tiempo", recordar:true, tiempo:-1});
			}
		}
	},
	validar:function(json){
		this.nombre=u.indefinido(json.nombre, "id");
		this.error=u.indefinido(json.error, null);
		this.accion=u.indefinido(json.accion, null);
		
		if(s.consultar(this.nombre)){
			if(this.accion!=null)this.accion();
		}
		else{
			if(this.error!=null)this.error();
		}
	}
};
var p={
	sesion_crear:function(json){
		this.nivel=u.indefinido(json.nivel, 0);
		this.nombre=u.indefinido(json.nombre, "");
		this.nombreNivelUno=u.indefinido(json.nombreNivelUno, "");
		this.valor=u.indefinido(json.valor, null);
		
		var j=this;
		var e=false;
		
		$.ajax({
			url:"php/peticiones.php",
			data:"peticion=sesion_array&nivel="+j.nivel+"&nombreSesion="+j.nombre+"&nombreNivelUno="+j.nombreNivelUno+"&valor="+j.valor,
			async:false,
			type:"POST",
			cache:false,
			dataType:"json",
			timeout:30000,
			error:function(jqXHR, status, error){
				$("#error").html(jqXHR.responseText);
			},
			success:function(datos){
				e = datos.sesiones.estatus;
			}
		});
		
		return e;
	},
	sesion_consultar:function(json){
		this.nombre=u.indefinido(json.nombre, "");
		this.nombreNivelUno=u.indefinido(json.nombreNivelUno, "");
		this.nivel=u.indefinido(json.nivel, 0);
		
		var j=this;
		var d=null;
		
		$.ajax({
			url:"php/peticiones.php",
			data:"peticion=consultar_sesion_array&nivel="+j.nivel+"&nombreSesion="+j.nombre+"&nombreNivelUno="+j.nombreNivelUno,
			async:false,
			type:"POST",
			cache:false,
			dataType:"json",
			timeout:30000,
			error:function(jqXHR, status, error){
				$("#error").html(jqXHR.responseText);
			},
			success:function(datos){
				d = datos.sesiones;
			}
		});
		
		return d;
	},
	sesion_eliminar:function(json){
		this.nombre=u.indefinido(json.nombre, "");
		this.nombreNivelUno=u.indefinido(json.nombreNivelUno, "");
		this.nivel=u.indefinido(json.nivel, 0);
		
		var j=this;
		var e=false;
		
		$.ajax({
			url:"php/peticiones.php",
			data:"peticion=eliminar_sesion_array&nivel="+j.nivel+"&nombreSesion="+j.nombre+"&nombreNivelUno="+j.nombreNivelUno,
			async:false,
			type:"POST",
			cache:false,
			dataType:"json",
			timeout:30000,
			error:function(jqXHR, status, error){
				$("#error").html(jqXHR.responseText);
			},
			success:function(datos){
				e = datos.sesiones.estatus;
			}
		});
		
		return e;
	}
};
// Copyright(c) 2013, eHg