<?php 
/* print_r($_FILES);
echo'<br>';
echo 'nombre de la imagen: ';
print_r($_FILES['imagen']['name']);

echo'<br>';
echo 'tipo de la imagen: ';
print_r($_FILES['imagen']['type']);

echo'<br>';
echo 'ruta temporal de la imagen: ';
print_r($_FILES['imagen']['tmp_name']);
 */
$conexion=mysql_connect('localhost','root','1p2') or die('No hay conexión a la base de datos');
$db=mysql_select_db('siao',$conexion)or die('no existe la base de datos.');
$titulo=$_POST['titulo'];
$precio=$_POST['precio'];
$marca=$_POST['marca'];
$modelo=$_POST['modelo'];
$desc=$_POST['descripcion_g'];
if(empty($titulo)){
	echo 'Error, Tiene que insertar el Titulo';
}else if(empty($precio)){
	echo 'Error, Tiene que insertar el Precio del Equipo';
}else if(empty($marca)){
	echo 'Error, Tiene que insertar la Marca';
}else if(empty($modelo)){
	echo 'Error, Tiene que insertar el Modelo';
}else if(empty($desc)){
	echo 'Error, Tiene que insertar la descripcion';
}else{
$rutaEnServidor='../img/fichas_tecnicas';
$rutaBD='img/fichas_tecnicas';
$rutaTemporal=$_FILES['imagen']['tmp_name'];
$nombreImagen=$_FILES['imagen']['name'];
$rutaDestino=$rutaEnServidor.'/'.$nombreImagen;
$rutaDestinoBD=$rutaBD.'/'.$nombreImagen;
move_uploaded_file($rutaTemporal,$rutaDestino);


$sql="INSERT INTO fichas_tecnicas (titulo,precio,marca,modelo,descripcion,ruta) values('".$titulo."','".$precio."','".$marca."','".$modelo."','".$desc."','".$rutaDestinoBD."')";
mysql_query("SET NAMES utf8");
$res=mysql_query($sql,$conexion);

if ($res){
		$query= mysql_query("SELECT MAX(id_ficha_tecnica) AS id FROM fichas_tecnicas");
 		if ($row = mysql_fetch_row($query)) 
 		{
   			$id = trim($row[0]);
 		}
	
	header('Location:reporte_fTecnica.php?id='.$id.'');
}else{
    echo 'no se puedo insertar';
} 
}
?>
<!-- 

<?php 
$conexion=mysql_connect('localhost','root','1p2') or die('No hay conexión a la base de datos');
$db=mysql_select_db('base',$conexion)or die('no existe la base de datos.');

$consulta=mysql_query("select * from datos");
while($filas=mysql_fetch_array($consulta)){
	$ruta=$filas['ruta'];
	$desc=$filas['descripcion'];


?>

<?php echo $desc;?><br>
<div style="width:400px !important;">
<img src="<?php echo $ruta; ?>" >
</div>
<br>

<?php }?> -->