<?php
$f=$HTTP_GET_VARS['f'];
//Comprobar el fichero (�no lo pase por alto!)
if(substr($f,0,3)!='tmp' or strpos($f,'/') or strpos($f,'\\'))
die('Nombre incorrecto de fichero');
if(!file_exists($f))
    die('El fichero no existe');
//Gestionar peticiones especiales de IE si es necesario
if($HTTP_ENV_VARS['USER_AGENT']=='contype')
{
    Header('Content-Type: application/pdf');
    exit;
}
//Devolver el PDF
Header('Content-Type: application/pdf');
Header('Content-Length: '.filesize($f));
readfile($f);
//Eliminar el fichero
unlink($f);
exit;
?>

<?php/*
function comprobar_email($email) {
    return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? 1 : 0;
}

if (isset($_POST['recibir'])) {
    if (comprobar_email($_POST['email'])) {
        require_once("classes/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->From = "from@domain.com";
        $mail->FromName = "Jose Aguilar";
        $mail->Subject = "Demo de PHPMailer";
        $mail->Body = "Hola, esto es una demo de envio de emails con archivos adjuntos!!!";
        $mail->AddAddress($_POST['email'], "User Name");
        $archivo = 'prueba.pdf';
        $mail->AddAttachment($archivo,$archivo);
        $mail->Send();
        echo '<p>Se ha enviado correctamente el email a '.$_POST['email'].'!</p>';
    }
    else {
        echo '<p>El email introducido no es correcto!</p>';
    }
}*/
?>
