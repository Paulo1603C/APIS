<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$email = isset($_POST['correo']) ? $_POST['correo'] : null;
$contraNew = isset($_POST['Contraseña']) ? $_POST['Contraseña'] : null;

if( $contraNew != null ){

    $update = "UPDATE usuarios SET Contraseña = :contrasena WHERE Correo = :correo ";
    $update = $connect->prepare($update);
    $update->bindParam(':correo', $email, PDO::PARAM_STR);
    $update->bindParam(':contrasena',$contraNew, PDO::PARAM_STR);
    try {
        $update->execute();
        if ($update->rowCount() > 0) {
            echo json_encode(['message' => 'contraseña Actualizada']);
        } else {
            echo json_encode(['message' => 'La contraseña no se pudo Actualizar']);
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo "Error" . $th;
    }
}else{
    echo json_encode(['Message' => 'NO existen datos para la consulta']);
}

?>