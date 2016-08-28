<?php
	//PHP Document
	// Copyright(c) 2013, eHg
	class CExcel
	{
		private $_ruta;
		private $_nombre = 'tmp';
		private $_descripcion = '';
		private $_fecha = '';
		private $_nombre_nuevo;
		private $_archivo;
		private $_objPHPExcel;
		private $_tipo;
		private $_hoja = 1;
		private $_extension;
		private $_objWriter;
		private $_estilos;
		private $_aplicar_estilos = false;
		private $_texto_tamano = 11;
		private $_texto_color = 'FF000000';
		private $_texto_negrita = false;
		private $_texto_alineacion_h = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
		private $_texto_alineacion_v = PHPExcel_Style_Alignment::VERTICAL_CENTER;
		private $_objRichText;
		private $_borde = 'allborders';
		private $_borde_estilo = PHPExcel_Style_Border::BORDER_NONE;
		private $_columna_ancho;
		private $_celda_tipo = PHPExcel_Style_Fill::FILL_NONE;
		private $_celda_color = 'FFFFFFFF';
		
		public function __construct($ruta = '', $nombre = '', $fecha = true)
		{
			$this->_ruta = $ruta;
			if(is_string($nombre) && $nombre != '') { $this->_nombre = $nombre; }
			if($fecha) { $this->_fecha = date('d-m-Y H.i.s'); }
		}
		public function abrir($archivo = 'temp.xlsx')
		{
			$this->_archivo = $this->_ruta.$archivo;
			$extension = pathinfo($this->_archivo);
			if($extension == 'xlsx' || $extension == 'xls')
			{
				$this->_tipo = 'excel';
			}
			$this->_objPHPExcel = PHPExcel_IOFactory::load($this->_archivo);
		}
		public function nuevo($tipo = 'excel')
		{
			$this->_objPHPExcel = new PHPExcel();
			$this->_tipo = $tipo;
		}
		public function plantilla($nombre_plantilla, $hoja = 0)
		{
			$objPHPExcel = PHPExcel_IOFactory::load($this->_ruta.$nombre_plantilla);
			if(is_int($hoja)) { $objWorkSheet = $objPHPExcel->getSheet($hoja); }
			else { $objWorkSheet = $objPHPExcel->getSheetByName($hoja); }
			$this->_descripcion = $objWorkSheet->getTitle();
			$this->_objPHPExcel->addExternalSheet($objWorkSheet,0);
			$this->_objPHPExcel->removeSheetByIndex(1);
			$this->_objPHPExcel->setActiveSheetIndex(0);
			if($this->_tipo == 'pdf') { $this->_objPHPExcel->getActiveSheet()->setShowGridLines(false); }
		}
		public function copiar()
		{
			$objWorkSheetTemplate = $this->_objPHPExcel->getSheet(0)->copy();
			$title = $objWorkSheetTemplate->getTitle();
			$objWorkSheetTemplate->setTitle($title.' '.$this->_hoja);
			$this->_objPHPExcel->addSheet($objWorkSheetTemplate);
			$this->_objPHPExcel->setActiveSheetIndex($this->_hoja);
			$this->_hoja++;
		}
		public function hoja_nueva($nombre = null)
		{
			$this->_objPHPExcel->createSheet();
			$indice = $this->_objPHPExcel->getSheetCount();
			$this->_objPHPExcel->setActiveSheetIndex(--$indice);
			if(!is_null($nombre)) { $this->pag_nombre($nombre); }
		}
		public function insertar($celda, $dato)
		{
			$this->_objPHPExcel->getActiveSheet()->setCellValue($celda, $dato);
			if($this->_aplicar_estilos) { $this->_objPHPExcel->getActiveSheet()->getStyle($celda)->applyFromArray($this->_estilos()); }
		}
		public function insertar_c($columna, $fila, $dato)
		{
			$this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columna, $fila, $dato);
			$this->_objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($columna, $fila)->applyFromArray($this->_estilos());
			if(!is_null($this->_columna_ancho)) { $this->_objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columna)->setWidth($this->_columna_ancho); }
		}
		public function leer()
		{
			$datos = array();
			foreach ($this->_objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				$hoja = $worksheet->getTitle();
				foreach ($worksheet->getRowIterator() as $row)
				{
					$fila = $row->getRowIndex();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(true);
					foreach ($cellIterator as $cell){
						$columna = $cell->getCoordinate();
						$datos[$hoja][$fila][$columna] = $cell->getCalculatedValue();
					}
				}
			}
			return $datos;
		}
		public function nombre($nombre) { $this->_nombre_nuevo = $nombre; }
		public function imagen($celda, $imagen, $tamano = null)
		{
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setPath($imagen);
			$objDrawing->setCoordinates($celda);
			if(!is_null($tamano)) { $objDrawing->setHeight($tamano); }
			$objDrawing->setWorksheet($this->_objPHPExcel->getActiveSheet());
		}
		public function pag_encabezado($izquierda = '', $centro = '', $derecha = '')
		{
			$this->_objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L'.$izquierda.'&C'.$centro.'&R'.$derecha);
		}
		public function pag_pie($izquierda = '', $centro = '', $derecha = '')
		{
			$this->_objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L'.$izquierda.'&C'.$centro.'&R'.$derecha);
		}
		public function pag_horizontal()
		{
			$this->_objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		}
		public function pag_ajustar($ancho = 1,$alto = 0)
		{
			$this->_objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth($ancho);
			$this->_objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight($alto);
		}
		public function pag_margenes($superior = 0.5, $derecho = 0.6, $inferior = 0.7, $izquerdo = 0.6, $encabezado = 2.9, $pie = 0.3)
		{
			$this->margen_superior($superior);
			$this->margen_derecho($derecho);
			$this->margen_inferior($inferior);
			$this->margen_izquierdo($izquerdo);
			$this->margen_encabezado($encabezado);
			$this->margen_pie($pie);
		}
		public function pag_nombre($nombre) { $this->_objPHPExcel->getActiveSheet()->setTitle(substr(str_replace(array(':','\\','/','?','*','[',']'), '', $nombre),0,30)); }
		public function margen_superior($superior) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setTop($superior); }
		public function margen_derecho($derecho) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setRight($derecho); }
		public function margen_inferior($inferior) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setBottom($inferior); }
		public function margen_izquierdo($izquerdo) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setLeft($izquerdo); }
		public function margen_encabezado($encabezado) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setHeader($encabezado); }
		public function margen_pie($pie) { $this->_objPHPExcel->getActiveSheet()->getPageMargins()->setFooter($pie); }
		public function aplicar_estilos($aplicar_estilos = true) { $this->_aplicar_estilos = $aplicar_estilos; }
		public function tex_tamano($tamano) { $this->_texto_tamano = $tamano; }
		public function tex_color($color) { $this->_texto_color = $color; }
		public function tex_negrita($negrita) { $this->_texto_negrita = $negrita; }
		public function tex_alineacion($horizontal = '', $vertical = '')
		{
			if($horizontal == 'izquierda') { $this->_texto_alineacion_h = PHPExcel_Style_Alignment::HORIZONTAL_LEFT; }
			else if($horizontal == 'derecha') { $this->_texto_alineacion_h = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT; }
			else if($horizontal == 'centro') { $this->_texto_alineacion_h = PHPExcel_Style_Alignment::HORIZONTAL_CENTER; }
			else if($horizontal == 'justificado') { $this->_texto_alineacion_h = PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY;}
			
			if($vertical == 'arriba') { $this->_texto_alineacion_v = PHPExcel_Style_Alignment::VERTICAL_TOP; }
			else if($vertical == 'abajo') { $this->_texto_alineacion_v = PHPExcel_Style_Alignment::VERTICAL_BOTTOM; }
			else if($vertical == 'medio') { $this->_texto_alineacion_v = PHPExcel_Style_Alignment::VERTICAL_CENTER; }
		}
		public function tex_avanzado()
		{
			$this->_objRichText = new PHPExcel_RichText();
		}
		public function tex_nuevo($texto = '', $tipo = 'Calibri', $tamano = 11, $negrita = false, $cursiva = false, $subrayado = false, $color = '000000', $tachado = false)
		{
			$objPayable = $this->_objRichText->createTextRun($texto);
			$objPayable->getFont()->applyFromArray(
				array(
					'name' => $tipo,
					'size' => $tamano,
					'bold' => $negrita,
					'italic' => $cursiva,
					'underline' => ($subrayado) ? PHPExcel_Style_Font::UNDERLINE_SINGLE : '',
					'color'	 => array(
						'rgb' => $color
					),
					'strike' => $tachado
				)
			);
		}
		public function tex_agregar()
		{
			return $this->_objRichText;
		}
		public function cel_borde($borde = 'contorno', $estilo = 'ninguno')
		{
			if($borde == 'todos') { $this->_borde = 'allborders'; }
			else if($borde == 'contorno') { $this->_borde = 'outline'; }
			
			if($estilo == 'ninguno') { $this->_borde_estilo = PHPExcel_Style_Border::BORDER_NONE; }
			else if($estilo == 'linea') { $this->_borde_estilo = PHPExcel_Style_Border::BORDER_THIN; }
			else if($estilo == 'abajo') { $this->_borde_estilo = PHPExcel_Style_Border::BORDER_MEDIUM	; }
		}
		public function cel_bordedos($celda){
			
			 $this->_objPHPExcel->getActiveSheet()->getStyle($celda)->getBorders()->applyFromArray(
         array(
             'bottom'     => array(
                 'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                 'color' => array(
                     'rgb' => '808080'
                 )
             ),
             'top'     => array(
                 'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                 'color' => array(
                     'rgb' => '808080'
                 )
             )
         )
 );
		} 
		public function cel_color($color = null)
		{
			if(is_null($color)) { $this->_celda_tipo = PHPExcel_Style_Fill::FILL_NONE; }
			else { $this->_celda_tipo = PHPExcel_Style_Fill::FILL_SOLID; }
			
			if(is_null($color)) { $this->_celda_color = 'FFFFFFFF'; }
			else { $this->_celda_color = $color; }
		}
		public function cel_combinar($celdas) { $this->_objPHPExcel->getActiveSheet()->mergeCells($celdas); }
		public function col_ancho($ancho) { $this->_columna_ancho = $ancho; }
		public function cell_alto(){
			$this->_objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(5);
		}
		private function _estilos()
		{
			return $this->_estilos = array(
				'font' => array(
					'size' => $this->_texto_tamano,
					'color' => array(
						'rgb' => $this->_texto_color
					),
					'bold' => $this->_texto_negrita
				),
				'borders' => array(
					$this->_borde => array(
						'style' => $this->_borde_estilo
					)
				),
				'alignment' => array(
					'horizontal' => $this->_texto_alineacion_h,
					'vertical' => $this->_texto_alineacion_v,
					'wrap' => true
				),
				'fill' => array(
					'type'       => $this->_celda_tipo,
					'startcolor' => array(
						'argb' => $this->_celda_color
					)
				)
			);
		}
		private function _crear($nombre = false)
		{
			if($this->_objPHPExcel->getSheetCount() > 1) { $this->_objPHPExcel->removeSheetByIndex(0); }
			if($this->_tipo == 'excel')
			{
				$this->_extension = '.xlsx';
				$this->_objWriter = PHPExcel_IOFactory::createWriter($this->_objPHPExcel, 'Excel2007');
			}
			else if($this->_tipo == 'pdf')
			{
				$this->_extension = '.pdf';
				$this->_objWriter = PHPExcel_IOFactory::createWriter($this->_objPHPExcel, 'PDF');
				$this->_objWriter->setFont('helvetica');
				$this->_objWriter->writeAllSheets();
			}
			if($nombre == true) { $this->_nombre = $this->_nombre_nuevo.' '.$this->_descripcion; }
			$this->_archivo = utf8_decode($this->_ruta.$this->_nombre.$this->_extension);
			// $this->_archivo = $this->_ruta.$this->_nombre.$this->_extension;
			$this->_nombre_nuevo .= ' '.$this->_descripcion.' '.$this->_fecha.$this->_extension;
		}
		public function guardar()
		{
			$this->_crear();
			$this->_objWriter->save($this->_archivo);
		}
		public function descargar()
		{
			$this->guardar();
			$size = filesize($this->_archivo);
			header('Content-type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$this->_nombre_nuevo.'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$size);
			readfile($this->_archivo);
		}
		public function ver()
		{
			if($this->_tipo == 'excel')
			{
				$this->descargar();
			}
			else if($this->_tipo == 'pdf')
			{
				$this->guardar();
				$size = filesize($this->_archivo);
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="'.$this->_nombre_nuevo.'"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: '.$size);
				readfile($this->_archivo);
			}
		}
		public function guardar_temp()
		{
			$this->_crear(true);
			$this->_objWriter->save($this->_archivo);
			return $this->_archivo;
		}
		public function descargar_temp()
		{
			if($this->_tipo == 'excel')
			{
				$this->_crear();
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="'.$this->_nombre_nuevo.'"');
				header('Cache-Control: max-age=0');
				$this->_objWriter->save('php://output');
			}
			else if($this->_tipo == 'pdf')
			{
				$this->_crear();
				header('Content-Type: application/pdf');
				header('Content-Disposition: attachment; filename="'.$this->_nombre_nuevo.'"');
				header('Cache-Control: max-age=0');
				$this->_objWriter->save('php://output');
			}
		}
		public function ver_temp()
		{
			if($this->_tipo == 'excel')
			{
				$this->descargar_temp();
			}
			else if($this->_tipo == 'pdf')
			{
				$this->_crear();
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="'.$this->_nombre_nuevo.'"');
				header('Cache-Control: max-age=0');
				$this->_objWriter->save('php://output');
			}
		}
	}
?>