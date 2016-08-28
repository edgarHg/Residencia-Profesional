<?php

require('../librerias/fpdf/fpdf.php');
require('conexion.php');
class PDF extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		
		$this->Rect($x,$y,$w,$h);

		$this->MultiCell($w,5,$data[$i],0,$a,'true');
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

function Header()
{
	$this->SetFont('Arial','',10);	
	$this->Image('../img/header_nota.jpg',-10 ,0, 235 , 55,'JPG');
	$this->Ln(40);
}

function Footer()
{
	$this->SetTextColor(0,0,0);
	$this->SetY(-45);
	$this->SetFont('Arial','B',11);
	$this->SetX(75);
	$this->Image('../img/firma.png',92,215, 30,30);
	$this->Cell(60,0,' ','B',0,'C');
	$this->SetX(0);
	$this->Cell(215,10,'JESUS DIAZ VERGARA',0,0,'C');
	$this->SetX(0);
	$this->SetFont('Arial','',10);
	$this->Cell(215,20,'Gerente General',0,0,'C');
	$this->SetX(0);
	$this->SetFont('Arial','',10);
	$this->Cell(215,30,'CARRETERA LA ISLA KM. 1, COL. MIGUEL HIDALGO, VILLAHERMOSA, CENTRO, TABASCO',0,0,'C');
	$this->SetX(0);
	$this->SetFont('Arial','',10);
	$this->Cell(215,40,'TEL:(9932) 630743 NEXTEL: 993254486 RADIO: 72*8*63142',0,0,'C');
	


}

}
	$id_servicio= $_GET['id'];
	$con = new DB;
	$cotizaciones = $con->conectar();	
	$strConsulta = "SELECT * from vw_servicios_finalizados where id_servicio =  '$id_servicio'";
	$cotizaciones = mysql_query($strConsulta);
	$fila = mysql_fetch_array($cotizaciones);
	
	$pdf=new PDF('P','mm','Letter');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(10,20,10);
	$pdf->SetX(164);
	$pdf->Cell(0,-4,''.$fila['fecha_nota'].'',0,1);
	$pdf->Ln(10);
	
    $pdf->SetFont('Arial','',12);
	$pdf->SetX(148);
	$pdf->Cell(0,6,'SERVICIO No:',0,1);
	$pdf->SetX(177);
	$pdf->Cell(0,-6,''.$fila['id_servicio'].'','T',1,'L');
	$pdf->Ln(7);
    $pdf->Cell(0,6,'RAZON SOCIAL:',0,1);
	$pdf->SetX(44);
	$pdf->Cell(0,-6,''.$fila['razon_social'].'','T',1,'L',false);
	$pdf->Ln(7);
	$pdf->Cell(0,6,'DOMICILIO:',0,1);
	$pdf->SetX(35);
	$pdf->Cell(0,-6,''.$fila['direccion_cliente'].'','T',1,'L',false);
	$pdf->Ln(7);
	$pdf->Cell(0,6,'ATENCION: ',0,1);
	$pdf->SetX(35); 
	$pdf->Cell(0,-6,''.$fila['atencion'].'','T',1,'L',false);
	$pdf->Ln(8);
	$pdf->Cell(0,6,'SERVICIO DE: ',0,1);
	$pdf->SetX(40); 
	$pdf->Cell(70,-6,''.$fila['servicio'].'','T',1,'L',false);
	$pdf->SetX(118); 
	$pdf->Cell(0,6,'MARCA: ',0,1);
	$pdf->SetX(136); 
	$pdf->Cell(70,-6,''.$fila['marca'].'','T',1,'L',false);
	$pdf->Ln(7);	
	$pdf->Cell(0,6,'MODELO: ',0,1);
	$pdf->SetX(31); 
	$pdf->Cell(40,-6,''.$fila['modelo'].'','T',1,'L',false);
	$pdf->SetX(75);
	$pdf->Cell(0,6,'No SERIE: ',0,1);
	$pdf->SetX(97);
	$pdf->Cell(40,-6,''.$fila['serie'].'','T',1,'L',false);
	$pdf->Ln(10);
	
	$pdf->SetWidths(array(25, 125, 25, 20));
	$pdf->SetFont('Arial','B',10);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.8);
	
    $pdf->SetTextColor(255);

		for($i=0;$i<1;$i++)
			{
				$pdf->SetFillColor(0,0,0);
				$pdf->Row(array('CANTIDAD', 'DESCRIPCION', 'P. UNITARIO', 'TOTAL'));
			}
	
	$historial = $con->conectar();	
	$strConsulta = "SELECT * FROM vw_detalle_nota_rm WHERE id_servicio = '$id_servicio'";
	$historial = mysql_query($strConsulta);
	$numfilas = mysql_num_rows($historial);
	
	for ($i=0; $i<$numfilas; $i++)
		{
			$fila = mysql_fetch_array($historial);
			$pdf->SetFont('Arial','',10);
			
				$pdf->SetFillColor(255,255,255);
    			$pdf->SetTextColor(0);
				$pdf->Row(array($fila['cantidad'], $fila['descripcion'], $fila['p_unitario'], $fila['p_total']));
			
		}
		
	$historial = $con->conectar();	
	$d_consulta = "SELECT * from vw_cotizaciones_general where id_servicio =  '$id_servicio'";
	$historial = mysql_query($d_consulta);
	$fila = mysql_fetch_array($historial);
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Ln(7);
	$pdf->SetX(165);
	$pdf->Cell(0,-6,'SUBTOTAL:',0,1,'L');
	$pdf->SetX(165);
	$pdf->Cell(40,6,''.$fila['subtotal'].'','B',1,'R',false);
	$pdf->Ln(7);
	$pdf->SetX(165);
	$pdf->Cell(0,-6,'IVA%:',0,1,'L');
	$pdf->SetX(165);
	$pdf->Cell(40,6,''.$fila['iva'].'','B',1,'R',false);
	$pdf->Ln(7);
	$pdf->SetX(165);
	$pdf->Cell(0,-6,'TOTAL:',0,1,'L');
	$pdf->SetX(165);
	$pdf->Cell(40,6,''.$fila['total'].'','B',1,'R',false);
	
	$observacion= $con->conectar();
	$strConsulta2 = "SELECT * from vw_servicios_finalizados where id_servicio =  '$id_servicio'";
	$observacion = mysql_query($strConsulta2);
	$fila2 = mysql_fetch_array($observacion);
	
	$pdf->SetY(180);
	$pdf->SetLineWidth(0.5);
	$pdf->SetFillColor(0,0,0);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(153,6,'RECOMENDACIONES:','RBTL',1,'L',true);
	$pdf->SetTextColor(0);
	$pdf->Ln(0);	
	$pdf->Multicell(153,5,''.$fila2['observacion_reparacion'].'','RBTL');


$pdf->Output();
?>