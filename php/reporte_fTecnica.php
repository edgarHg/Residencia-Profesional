<?php
require('../librerias/fpdf/fpdf.php');
require('conexion.php');
ini_set('default_charset','utf-8');
class PDF extends FPDF
{
function Header()
{
	$this->SetFont('Arial','',10);	
	$this->Image('../img/header_ficha_t.jpg',-10 ,0, 235 , 55,'JPG');
	$this->Ln(40);
}
function Footer()
{
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
	$id_fTecnica= $_GET['id'];
	$con = new DB;
	$servicios = $con->conectar();	
	$fecha_hoy=date('d / m / Y');
	$strConsulta = "SELECT * FROM fichas_tecnicas WHERE id_ficha_tecnica=  '$id_fTecnica'";
	$servicios = mysql_query($strConsulta);
	$fila = mysql_fetch_array($servicios);

	$pdf=new PDF('P','mm','Letter');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(10,20,10);
	$pdf->SetX(164);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,-3,$fecha_hoy,0,1,'C');
	$pdf->Ln(10);
		
	$pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,6,''.$fila['titulo'].'',0,1,'C');
	$pdf->Image('../'.$fila['ruta'].'',75,65, 65,65);
	$pdf->Ln(70);
	$pdf->Cell(0,6,'PRECIO: '.$fila['precio'].'','',1,'C',false);
	$pdf->Ln(4);
	$pdf->SetFont('Arial','',12);
	$pdf->SetFillColor(0,0,0);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(25,6,'MARCA:','TLRB',1,'L	',true);
	$pdf->SetY(143);
	$pdf->SetX(35);
	$pdf->SetTextColor(0);
	$pdf->SetLineWidth(0.2);
	$pdf->Cell(70,6,''.$fila['marca'].'','TLRB',1,'L',false);
	$pdf->SetY(143);
	$pdf->SetX(105);
	$pdf->SetFillColor(0,0,0);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(25,6,'MODELO: ',0,1,'L',true);
	
	$pdf->SetTextColor(0);
	$pdf->SetX(130); 
	$pdf->Cell(0,-6,''.$fila['modelo'].'','TLRB',1,'L',false);
	$pdf->Ln(6);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(0,6,'DESCRIPCION GENERAL','TLRB',1,'C',true);
	$pdf->SetTextColor(0);
	$pdf->Ln(1);	
	$pdf->Line(10,210,10,150);
	$pdf->SetFont('Arial','',14);	
	$pdf->Multicell(195,6,''.$fila['descripcion'].'',0);
	$pdf->Line(206,210,206,150);
	$pdf->Line(10,210,206,210);
	
	$pdf->Output();

?>