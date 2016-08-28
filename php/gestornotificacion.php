<?php
	// PHP Document
// Copyright(c) 2013, eHg @softekar
	
	function gestorNotificacion($errno, $errstr, $errfile, $errline){
		if (!(error_reporting() & $errno)){
			// Este código de error no está incluido en error_reporting
			return;
		}

		switch ($errno){
			case E_ERROR:
				$mensaje = "<b>Error fatal [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				$mensaje .= ". Abortado...";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			case E_WARNING:
				$mensaje = "<b>Advertencia [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			case E_NOTICE:
				$mensaje = "<b>Nota [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			case E_USER_ERROR:
				$mensaje = "<b>Error fatal [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				$mensaje .= ", Abortado...";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			case E_USER_WARNING:
				$mensaje = "<b>Advertencia [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			case E_USER_NOTICE:
				$mensaje = "<b>Nota [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
			
			default:
				$mensaje = "<b>Tipo de error desconocido [$errno]:</b> $errstr en el archivo <b>$errfile</b>, línea <b>$errline</b>";
				$mensaje .= ". PHP " . PHP_VERSION . " (" . PHP_OS . ")";
				echo CNotificacion::errorServidor($mensaje);
				exit(1);
				break;
		}
		/* No ejecutar el gestor de errores interno de PHP */
		return true;
	}
?>