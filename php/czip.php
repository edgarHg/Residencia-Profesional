<?php
	// PHP Document
	// Copyright(c) 2013, eHg @softekar
	
	Class CZip{
		private $_zip;
		private $_ruta = '';
		private $_archivo;
		private $_nombre = 'tmp';
		
		public function __construct($ruta = null, $nombre = null){
			if(!is_null($ruta)) { $this->_ruta = $ruta; }
			if(!is_null($nombre)) { $this->_nombre = $nombre; }
		}
		public function nuevo(){
			$this->_archivo = $this->_ruta.$this->_nombre.'.zip';
			$this->_zip = new PclZip($this->_archivo);
			$numero = $this->_zip->properties();
			if($numero['nb'] == 0) { unlink($this->_archivo); }
			else { $this->_zip->delete(); }
		}
		public function abrir(){
			$this->_archivo = $this->_ruta.$this->_nombre.'.zip';
			$this->_zip = new PclZip($this->_archivo);
		}
		public function agregar($nombre, $borrar = false){
			$this->_zip->add($nombre,PCLZIP_OPT_REMOVE_ALL_PATH);
			if($borrar) { unlink($nombre); }
		}
		public function descargar($nombre = null){
			if(!is_null($nombre)) { $this->_nombre = $nombre; }
			$size = filesize($this->_archivo);
			header('Content-type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$this->_nombre.'.zip"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$size);
			readfile($this->_archivo);
		}
	}
?>