<?php

require('../librerias/fpdf/fpdf.php');
require('conexion.php');
class PDF extends FPDF
{
function Header()
{
	$this->SetFont('Arial','',10);	
	$this->Image('../img/header_servicio.jpg',-10 ,0, 235 , 55,'JPG');
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

function CleanFiles($dir)
{
    //Borrar los ficheros temporales
    $t=time();
    $h=opendir($dir);
    while($file=readdir($h))
    {
        if(substr($file,0,3)=='tmp' and substr($file,-4)=='.pdf')
        {
            $path=$dir.'/'.$file;
            if($t-filemtime($path)>3600)
                @unlink($path);
        }
    }
    closedir($h);
}

}
	$id_servicio= $_GET['id'];
	$con = new DB;
	$servicios = $con->conectar();	
	
	$strConsulta = "SELECT * from vw_servicios where id_servicio =  '$id_servicio'";
	
	$servicios = mysql_query($strConsulta);
	
	$fila = mysql_fetch_array($servicios);

	$pdf=new PDF('P','mm','Letter');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(10,20,10);
	$pdf->SetX(164);
	$pdf->Cell(0,-4,''.$fila['fecha_creacion'].'',0,1);
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
	$pdf->Ln(7);
	$pdf->Cell(0,6,'TEL CELULAR: ',0,1);
	$pdf->SetX(41); 
	$pdf->Cell(0,-6,''.$fila['tel_atencion'].'','T',1,'L',false);
	$pdf->Ln(10);
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
	$pdf->Cell(0,6,'FALLA QUE PRESENTA:',0,1);	
	$pdf->Line(10,170,10,120);
	$pdf->Line(10,120,207,120);
	$pdf->Ln(4);	
	$pdf->Multicell(195,5,''.$fila['descripcion_falla'].'',0);
	$pdf->Line(207,170,207,120);
	$pdf->Line(10,170,207,170);
	$pdf->SetY(180);
	$pdf->Cell(0,6,'ACCESORIOS:',0,1);
	$pdf->Ln(8);
	$pdf->Cell(0,6,'MANGUERA:',0,1);
	$pdf->SetX(37);
	$pdf->Cell(20,-6,''.$fila['manguera'].'','T',1,'L',false);
	$pdf->SetX(64);
	$pdf->Cell(0,6,'PISTOLA:',0,1);
	$pdf->SetX(84);
	$pdf->Cell(20,-6,''.$fila['pistola'].'','T',1,'L',false);
	$pdf->SetX(110);
	$pdf->Cell(0,6,'LANZA:',0,1);
	$pdf->SetX(126);
	$pdf->Cell(20,-6,''.$fila['lanza'].'','T',1,'L',false);
	$pdf->SetX(153);
	$pdf->Cell(0,6,'BOQUILLA:',0,1);
	$pdf->SetX(177);
	$pdf->Cell(25,-6,''.$fila['boquilla'].'','T',1,'L',false);
	
	// //Determinar un nombre temporal de fichero en el directorio actual
	// $file=basename(tempnam(getcwd(),'tmp'));
	// rename($file,$file.'.pdf');
	// $file.='.pdf';
	// //Guardar el PDF en un fichero
	// $pdf->CleanFiles('tmp*.pdf');
	// $pdf->Output($file);
	// //Redirección con JavaScript
	// echo "<HTML><SCRIPT>document.location='$file';</SCRIPT></HTML>";
	$pdf->Output();

?>