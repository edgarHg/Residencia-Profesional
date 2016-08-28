<?php
	// PHP Document
	// Copyright(c) 2013, eHg @softekar
	
	ini_set('display_errors',1);
	// ini_set('memory_limit','256M');
	// ini_set('max_execution_time','120');
	ini_set('default_charset','utf-8');
	error_reporting(E_ALL ^ E_WARNING);
	require_once 'gestornotificacion.php';
	set_error_handler('gestorNotificacion');
	date_default_timezone_set('America/Mexico_City');
	extract($_REQUEST);
	session_start();
?>