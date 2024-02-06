<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$email = isset($_POST['correo']) ? $_POST['correo'] : null;
if ($email != null) {

    $select = "SELECT u.Correo FROM usuarios as u WHERE u.Correo=:correo ";
    $select = $connect->prepare($select);
    $select->bindParam(':correo', $email, PDO::PARAM_STR);
    try {
        $select->execute();
        $result = $select->fetch(PDO::FETCH_COLUMN);
        if ($result) {
            echo json_encode('existe');
        } else {
            echo json_encode('no existe');
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo "Error" . $th;
    }
} else {
    echo json_encode(['Message' => 'NO existen datos para la consulta']);
}

?>