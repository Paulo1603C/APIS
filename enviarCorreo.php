<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    //Luego tenemos que iniciar la validación por SMTP:
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.office365.com"; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
    $mail->Username = "pauloalexis24@gmail.com"; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
    $mail->Password = "death243"; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
    $mail->Port = 465; // Puerto de conexión al servidor de envio. 
    $mail->From = "pauloalexis24@gmail.com"; // A RELLENARDesde donde enviamos (Para mostrar). Puede ser el mismo que el email creado previamente.
    $mail->FromName = "Paulo"; //A RELLENAR Nombre a mostrar del remitente. 
    $mail->AddAddress("paulomartinez1999@gmail.com"); // Esta es la dirección a donde enviamos 
    $mail->IsHTML(true); // El correo se envía como HTML 
    $mail->Subject = "Incidencia "; // Este es el titulo del email. 
    $mail->Body =
        "Esto es un correo generado desde la web, si quiere mas informacion contacte con: correo@gmail.com ";
    $exito = $mail->Send(); // Envía el correo.
    if ($exito) {
        echo "<script>alert('bien');location.href ='javascript:history.back()';</script>";
    } else {
        echo "<script>alert('Error al enviar el formulario')</script>";
        var_dump($_POST);
        exit();
    }
} catch (\Throwable $th) {
    //throw $th;
    echo "Mensaje no enviado " . $th;
}
?>