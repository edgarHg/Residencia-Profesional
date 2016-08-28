<?php
	// PHP Document
	// Copyright(c) 2013, eHg
	
	class CConsulta{
		private $_conexion, $_resultado;
		
		public function __construct($host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD, $db=DB_NAME){
			$this->_conectar($host, $user, $password, $db);
		}
		private function _conectar($host, $user, $password, $db){
			$this->_conexion = mysql_connect($host, $user, $password);
			if(!$this->_conexion) { die(CNotificacion::errorServidor('<b>Error al conectar[' . mysql_errno() . ']:</b> ' . mysql_error())); }
			
			$base_datos = mysql_select_db($db, $this->_conexion);
			if(!$base_datos) { die(CNotificacion::errorServidor('<b>No se puede usar la base de datos[' . mysql_errno() . ']:</b> ' . mysql_error())); }
			
			mysql_query("SET NAMES utf8");
		}
		public function sentencia($query){
			$this->_resultado = mysql_query($query,$this->_conexion);
			if(!$this->_resultado) { die(CNotificacion::errorServidor('<b>Consulta no válida[' . mysql_errno() . ']:</b> ' . mysql_error())); }
		}
		public function filas(){
			return mysql_fetch_array($this->_resultado); 
		}
		public function resultados(){
			return mysql_num_rows($this->_resultado);
		}
		public function id(){
			return mysql_insert_id($this->_conexion);
		}
		public function cerrar(){
			mysql_close($this->_conexion);
		}
	}
	class CConsultaMejorada{
		private $_conexion, $_resultado, $_stmt;
		
		public function __construct($host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD, $db=DB_NAME){
			$this->_conectar($host, $user, $password, $db);
		}
		private function _conectar($host, $user, $password, $db){
			$this->_conexion = mysqli_connect($host, $user, $password, $db);
			if(!$this->_conexion) { die(CNotificacion::errorServidor('<b>Error al conectar[' . mysqli_connect_errno() . ']:</b> ' . mysqli_connect_error())); }
			
			if(!mysqli_set_charset($this->_conexion, "utf8")){
				die(CNotificacion::errorServidor('<b>Error cargando el conjunto de caracteres utf8:[' . mysqli_errno($this->_conexion) . ']:</b> ' . mysqli_error($this->_conexion)));
			}
		}
		public function sentencia($query){
			$this->_resultado = mysqli_query($this->_conexion, $query);
			if(!$this->_resultado) { die(CNotificacion::errorServidor('<b>Consulta no válida[' . mysqli_errno($this->_conexion) . ']:</b> ' . mysqli_error($this->_conexion))); }
		}
		public function preparar($query, $tipos, $valores){
			$v=$valores;
			
			$this->_stmt = mysqli_prepare($this->_conexion, $query);
			if(!$this->_stmt) { die(CNotificacion::errorServidor('<b>Error al preparar consulta[' . mysqli_errno($this->_conexion) . ']:</b> ' . mysqli_error($this->_conexion))); }
			
			// $estatus = mysqli_stmt_bind_param($this->_stmt, $tipos, $valores);
			/*if(!$estatus){
				die(CNotificacion::errorServidor('<b>Error al vincular variables[' . mysqli_stmt_errno($this->_stmt) . ']:</b> ' . mysqli_stmt_error($this->_stmt)));
			}*/
			
			array_unshift($valores, $this->_stmt, $tipos);
			$parametros = array();
			foreach($valores as $clave => $valor) $parametros[$clave] = &$valores[$clave];
			$estatus=call_user_func_array('mysqli_stmt_bind_param', $parametros); 
			if(!$estatus){
				$marcadores = mysqli_stmt_param_count($this->_stmt);
				die(CNotificacion::errorServidor('<b>Error al vincular variables[' . mysqli_stmt_errno($this->_stmt) . ']:</b> ' . mysqli_stmt_error($this->_stmt) . ' La sentencia tiene ' . $marcadores . ' marcador(es).' . ' Parametro(s) ' . count($v). '.'));
			}
			if(!mysqli_stmt_execute($this->_stmt)){
				die(CNotificacion::errorServidor('<b>Error al ejecutar la sentencia[' . mysqli_stmt_errno($this->_stmt) . ']:</b> ' . mysqli_stmt_error($this->_stmt)));
			}
			
			$this->_resultado = mysqli_stmt_get_result($this->_stmt);
		}
		public function filas(){
			return mysqli_fetch_array($this->_resultado); 
		}
		public function resultados(){
			return mysqli_num_rows($this->_resultado);
		}
		public function id(){
			return mysqli_insert_id($this->_conexion);
		}
		public function cerrar(){
			mysqli_close($this->_conexion);
		}
	}
?>