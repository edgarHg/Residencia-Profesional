<?php
	// PHP Document
	// Copyright(c) 2013, eHg @softekar
	
	/* Configuracion */
	require_once 'configuracion.php';
	require_once 'parametros.php';	
	/* Librerias */
	include_once '../librerias/pclzip/pclzip.lib.php';
	include_once '../librerias/phpexcel/PHPExcel.php';
	include_once '../librerias/phpexcel/PHPExcel/IOFactory.php';
	include_once '../librerias/jpgraph/jpgraph.php';
	include_once '../librerias/jpgraph/jpgraph_line.php';
	include_once '../librerias/jpgraph/jpgraph_bar.php';
	include_once '../librerias/jpgraph/jpgraph_pie.php';
	include_once '../librerias/jpgraph/jpgraph_pie3d.php';
	include_once '../librerias/jpgraph/jpgraph_mgraph.php';
	require_once 'cnotificacion.php';
	require_once 'cconsulta.php';
	require_once 'cexcel.php';
	require_once 'czip.php';
	require_once 'funciones.php';
	
	/*  Acciones */
	if(isset($reporte))
	{
		if($reporte==1 || $reporte=="reporte_1")
		{
			$consulta = new CConsulta(); //inicializamos la consulta
			$consulta->sentencia("SELECT * FROM catalogos WHERE id_padre=$id_padre"); //consulta, como vez hacemos uso del parametro q enviamos
			$documento = new CExcel(RUTA_ARCHIVOS); //Aqui indicamos la ruta desde donde leera el archivo, en este caso archivos/... q ya tengo en parametros
			$documento->nuevo("pdf"); //formato del reporte pdf o excel
			$documento->plantilla("reportes/Prueba.xlsx", 0); //ruta y nombre del reporte apartir de la carpeta archivos, y como parametro inicial cual hoja del archivo excel va a usar, empeiza desde 0
			$i=12; //apartir de donde vamos a empzar a escribir en este caso desde la linea 12
			while($fila = $consulta->filas())
			{
				//Aqui los campos
				
				//hay dos formas de insertar los datos
				
				$documento->insertar("B13", $fila["id_concepto"]); //esto ingresando directamente en q celda los vas a colocar, ahora q si es una lista puedes usar este otro
				
				$documento->insertar_c(7, $i, $fila["descripcion"]);
				$i++; //aumentamos la fila
			}
			//diferentes metodos para generar el reporte
			//$documento->descargar_temp(); 
			//$documento->descargar(); 
			$documento->ver_temp();
		}
		if($reporte==2 || $reporte=="lista_1")
		{
			$consulta = new CConsulta(); //inicializamos la consulta
			$documento = new CExcel(RUTA_ARCHIVOS); //Aqui indicamos la ruta desde donde leera el archivo, en este caso archivos/... q ya tengo en parametros
			$documento->nuevo("excel"); //formato del reporte pdf o excel
			$documento->plantilla("reportes/lista.xlsx", 0); //ruta y nombre del reporte apartir de la carpeta archivos, y como parametro inicial cual hoja del archivo excel va a usar, empeiza desde 0
			$documento->pag_margenes();
			
			$consulta->sentencia("SELECT * FROM vw_cursos_actuales WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{	
				$documento->insertar("C5", $fila["nombre_curso"]);
				$documento->insertar("A8", $fila["clave"]);
				$documento->insertar("C8", $fila["lugar"]);
				$documento->insertar("J8", $fila["horario"]);
				$documento->insertar("M8", $fila["horas"]);				
			}
			
			$consulta->sentencia("SELECT * FROM vw_lista_curso WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				 $documento->cel_borde("todos","linea");
				//$documento->insertar("C3", $fila["id_concepto"]);
				$documento->tex_tamano(8);
				$documento->insertar_c(0, $i, $fila["nombre_completo"]);
				$documento->insertar_c(2, $i, $fila["ficha"]);
				$i++; //aumentamos la fila
			}
			
			$consulta->sentencia("SELECT * FROM vw_cursos_actuales WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{
				$documento->insertar("A34", $fila["instructor"]);
			}
			$consulta->sentencia("SELECT * FROM vw_lista_horarios WHERE id_curso=$id_curso order by dia");
			$a=4;
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(7.5);
				$documento->insertar_c($a, 11, $fila["fechas"]);
				$a++;				
			}
			$consulta->sentencia("SELECT * FROM vw_lista_asistencia WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(8);	
				$documento->insertar_c(4, $i, $fila["dia_1"]);
				$documento->insertar_c(5, $i, $fila["dia_2"]);
				$documento->insertar_c(6, $i, $fila["dia_3"]);
				$documento->insertar_c(7, $i, $fila["dia_4"]);
				$documento->insertar_c(8, $i, $fila["dia_5"]);
				$documento->insertar_c(9, $i, $fila["dia_6"]);
				$documento->insertar_c(10, $i, $fila["dia_7"]);
				$documento->insertar_c(11, $i, $fila["dia_8"]);
				$documento->insertar_c(12, $i, $fila["dia_9"]);
				$documento->insertar_c(13, $i, $fila["dia_10"]);
				$i++;				
			}
			//diferentes metodos para generar el reporte
			//$documento->descargar_temp(); 
			$documento->descargar(); 
			//$documento->ver_temp();
			//Creado 2013, EHG
		}
		if($reporte==3 || $reporte=="lista_impartidos")
		{
			$consulta = new CConsulta(); //inicializamos la consulta
			$documento = new CExcel(RUTA_ARCHIVOS); //Aqui indicamos la ruta desde donde leera el archivo, en este caso archivos/... q ya tengo en parametros
			$documento->nuevo("excel"); //formato del reporte pdf o excel
			$documento->plantilla("reportes/lista.xlsx", 0); //ruta y nombre del reporte apartir de la carpeta archivos, y como parametro inicial cual hoja del archivo excel va a usar, empeiza desde 0
			$documento->pag_margenes();
			
			$consulta->sentencia("SELECT * FROM vw_cursos_impartidos WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{	
				$documento->insertar("C5", $fila["nombre_curso"]);
				$documento->insertar("A8", $fila["clave"]);
				$documento->insertar("C8", $fila["lugar"]);
				$documento->insertar("J8", $fila["horario"]);
				$documento->insertar("M8", $fila["horas"]);				
			}
			
			$consulta->sentencia("SELECT * FROM vw_lista_curso_impartidos WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				 $documento->cel_borde("todos","linea");
				//$documento->insertar("C3", $fila["id_concepto"]);
				$documento->tex_tamano(8);
				$documento->insertar_c(0, $i, $fila["nombre_completo"]);
				$documento->insertar_c(2, $i, $fila["ficha"]);
				$i++; //aumentamos la fila
			}
			
			$consulta->sentencia("SELECT * FROM vw_cursos_actuales WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{
				$documento->insertar("A34", $fila["instructor"]);
			}
			$consulta->sentencia("SELECT * FROM vw_lista_horarios WHERE id_curso=$id_curso order by dia");
			$a=4;
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(7.5);
				$documento->insertar_c($a, 11, $fila["fechas"]);
				$a++;				
			}
			$consulta->sentencia("SELECT * FROM vw_lista_asistencia WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(8);	
				$documento->insertar_c(4, $i, $fila["dia_1"]);
				$documento->insertar_c(5, $i, $fila["dia_2"]);
				$documento->insertar_c(6, $i, $fila["dia_3"]);
				$documento->insertar_c(7, $i, $fila["dia_4"]);
				$documento->insertar_c(8, $i, $fila["dia_5"]);
				$documento->insertar_c(9, $i, $fila["dia_6"]);
				$documento->insertar_c(10, $i, $fila["dia_7"]);
				$documento->insertar_c(11, $i, $fila["dia_8"]);
				$documento->insertar_c(12, $i, $fila["dia_9"]);
				$documento->insertar_c(13, $i, $fila["dia_10"]);
				$i++;				
			}
			//diferentes metodos para generar el reporte
			//$documento->descargar_temp(); 
			$documento->descargar(); 
			//$documento->ver_temp();
			//Creado 2013, EHG
		}
		if($reporte==4 || $reporte=="calificaciones")
		{
			$consulta = new CConsulta(); 
			$documento = new CExcel(RUTA_ARCHIVOS); 
			$documento->nuevo("excel"); 
			$documento->plantilla("reportes/calificacion.xlsx", 0); 
			
			$consulta->sentencia("SELECT * FROM vw_cursos_actuales WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(8);	
				$documento->insertar("A7", $fila["clave"]);
				$documento->insertar("C7", $fila["nombre_curso"]);
				$documento->insertar("J7", $fila["nivel_capacitacion"]);
				$documento->insertar("M7", $fila["clave_especialidad"]);
				$documento->insertar("C8", $fila["centro_trabajo"]);
				$documento->insertar("K8", $fila["departamento"]);
				$documento->tex_tamano(8);
				$documento->insertar("C9", $fila["horas"]);	
				$documento->insertar("J10", $fila["fecha_inicio"]);
				$documento->insertar("M10", $fila["fecha_fin"]);
				$documento->insertar("B33", $fila["instructor"]);
			}
			$consulta->sentencia("SELECT * FROM vw_lista_curso WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				$documento->cel_borde("todos","linea");
				$documento->tex_tamano(8);
				$documento->insertar_c(0, $i, $fila["ficha"]);
				$documento->insertar_c(1, $i, $fila["nombre_completo"]);
				$documento->tex_tamano(6);
				$documento->insertar_c(4, $i, $fila["categoria"]);
				$documento->tex_tamano(8);
				$documento->insertar_c(7, $i, $fila["nivel"]);
				$documento->insertar_c(8, $i, $fila["escolaridad"]);
				$documento->insertar_c(9, $i, $fila["calificacion_teorica"]);
				$documento->insertar_c(10, $i, $fila["calificacion_practica"]);
				$documento->insertar_c(11, $i, $fila["calificacion_asistencia"]);
				$documento->insertar_c(12, $i, $fila["calificacion_final"]);
				$i++;
			}			
			//diferentes metodos para generar el reporte
			//$documento->descargar_temp(); 
			$documento->descargar(); 
			//$documento->ver_temp();
			//Creado 2013, EHG
		}
if($reporte==5 || $reporte=="calificaciones")
		{
			$consulta = new CConsulta(); 
			$documento = new CExcel(RUTA_ARCHIVOS); 
			$documento->nuevo("excel"); 
			$documento->plantilla("reportes/calificacion.xlsx", 0); 
			
			$consulta->sentencia("SELECT * FROM vw_cursos_impartidos WHERE id_curso=$id_curso");
			while($fila = $consulta->filas())
			{
				$documento->tex_tamano(8);	
				$documento->insertar("A7", $fila["clave"]);
				$documento->insertar("C7", $fila["nombre_curso"]);
				$documento->insertar("J7", $fila["nivel_capacitacion"]);
				$documento->insertar("M7", $fila["clave_especialidad"]);
				$documento->insertar("C8", $fila["centro_trabajo"]);
				$documento->insertar("K8", $fila["departamento"]);
				$documento->tex_tamano(8);
				$documento->insertar("C9", $fila["horas"]);	
				$documento->insertar("J10", $fila["fecha_inicio"]);
				$documento->insertar("M10", $fila["fecha_fin"]);
				$documento->insertar("B33", $fila["instructor"]);
			}
			$consulta->sentencia("SELECT * FROM vw_lista_curso_impartidos WHERE id_curso=$id_curso");
			$i=12;
			while($fila = $consulta->filas())
			{
				$documento->cel_borde("todos","linea");
				$documento->tex_tamano(8);
				$documento->insertar_c(0, $i, $fila["ficha"]);
				$documento->insertar_c(1, $i, $fila["nombre_completo"]);
				$documento->tex_tamano(6);
				$documento->insertar_c(4, $i, $fila["categoria"]);
				$documento->tex_tamano(8);
				$documento->insertar_c(7, $i, $fila["nivel"]);
				$documento->insertar_c(8, $i, $fila["escolaridad"]);
				$documento->insertar_c(9, $i, $fila["calificacion_teorica"]);
				$documento->insertar_c(10, $i, $fila["calificacion_practica"]);
				$documento->insertar_c(11, $i, $fila["calificacion_asistencia"]);
				$documento->insertar_c(12, $i, $fila["calificacion_final"]);
				$i++;
			}			
			//diferentes metodos para generar el reporte
			//$documento->descargar_temp(); 
			$documento->descargar(); 
			//$documento->ver_temp();
			//Creado 2013, EHG
		}
	}
	// Copyright(c) 2013, eHg @softekar
?>