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
	if(isset($peticion))
	{
		$datos = array();
		if($peticion==101 || $peticion=="mysql")
		{
			$consulta = new CConsulta();
			$consulta->sentencia($sentencia);
			$datos["resultados"]=$consulta->resultados();
			while($fila = $consulta->filas())
			{
				foreach($fila as $k => $v){
					if($v==null) $fila[$k]="";
				}
				$datos["filas"][] = $fila;
				foreach($fila as $clave => $valor)
				{
					$datos["columnas"][$clave][] = $valor;
				}
			}
			$datos["id"]=$consulta->id();
			$consulta->cerrar();
		}
		else if($peticion==102 || $peticion=="mysqli")
		{
			$consulta = new CConsultaMejorada();
			$consulta->sentencia($sentencia);
			$datos["resultados"]=$consulta->resultados();
			while($fila = $consulta->filas())
			{
				foreach($fila as $k => $v){
					if($v==null) $fila[$k]="";
				}
				$datos["filas"][] = $fila;
				foreach($fila as $clave => $valor)
				{
					$datos["columnas"][$clave][] = $valor;
				}
			}
			$datos["id"]=$consulta->id();
			$consulta->cerrar();
		}
		else if($peticion==103 || $peticion=="mysqli_stmt")
		{
			foreach($valor as $k => $v){
				if($v=="" or $v=="null") $valor[$k]=null;
			}
			$consulta = new CConsultaMejorada();
			$consulta->preparar($sentencia, $tipos, $valor);
			$datos["resultados"]=$consulta->resultados();
			while($fila = $consulta->filas())
			{
				foreach($fila as $k => $v){
					if($v==null) $fila[$k]="";
				}
				$datos["filas"][] = $fila;
				foreach($fila as $clave => $valor)
				{
					$datos["columnas"][$clave][] = $valor;
				}
			}
			$datos["id"]=$consulta->id();
			$consulta->cerrar();
		}
		else if($peticion==104 || $peticion=="buscar_archivo")
		{
			$ruta="../";
			$ruta.=($nombreCarpeta) ? trim($nombreCarpeta, "/") : "";
			$ruta.=($nombreArchivo) ? "/".trim($nombreArchivo, "/") : "";
			$datos["existe"]=(file_exists($ruta)) ? true : false;
		}
		else if($peticion==105 || $peticion=="subir_archivo")
		{
			$carpeta="../";
			$carpeta.=($nombreCarpeta) ? trim($nombreCarpeta, "/") : "";
			if(!file_exists($carpeta)){
				mkdir($carpeta, 0, true);
			}
			if(!empty($_FILES)){
				$ruta="../";
				$ruta.=($nombreCarpeta) ? trim($nombreCarpeta, "/") : "";
				$ruta.=($nombreArchivo) ? "/".trim($nombreArchivo, "/") : "";
				$archivo = $_FILES["archivo"]["tmp_name"];
				$datos["exito"]=(move_uploaded_file($archivo,$ruta)) ? true : false;
			}
		}
		else if($peticion==106 || $peticion=="sesion_array")
		{
			switch($nivel){
				case 0:
					$_SESSION[$nombreSesion][]=$valor;
					$datos["sesiones"]["estatus"]=(isset($_SESSION[$nombreSesion])) ? true : false;
					break;
				case 1:
					$_SESSION[$nombreSesion][$nombreNivelUno][]=$valor;
					$datos["sesiones"]["estatus"]=(isset($_SESSION[$nombreSesion][$nombreNivelUno])) ? true : false;
					break;
			}
		}
		else if($peticion==106 || $peticion=="consultar_sesion_array")
		{
			switch($nivel){
				case 0:
					$datos["sesiones"]=$_SESSION[$nombreSesion];
					break;
				case 1:
					if(isset($_SESSION[$nombreSesion][$nombreNivelUno])) $datos["sesiones"]=$_SESSION[$nombreSesion][$nombreNivelUno];
					else $datos["sesiones"][]=0;
					break;
			}
		}
		else if($peticion==107 || $peticion=="eliminar_sesion_array")
		{
			switch($nivel){
				case 0:
					unset($_SESSION[$nombreSesion]);
					$datos["sesiones"]["estatus"]=(empty($_SESSION[$nombreSesion])) ? true : false;
					break;
				case 1:
					unset($_SESSION[$nombreSesion][$nombreNivelUno]);
					$datos["sesiones"]["estatus"]=(empty($_SESSION[$nombreSesion][$nombreNivelUno])) ? true : false;
					break;
			}
		}
		echo json_encode($datos);
	}
	// echo "ok";
	/* Pruebas */
	// trigger_error("Mensaje", E_USER_NOTICE);
	// trigger_error("Mensaje", E_USER_WARNING);
	// trigger_error("Mensaje", E_USER_ERROR);
	
?>