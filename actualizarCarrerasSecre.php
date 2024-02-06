<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$idCarPer = isset($_POST['carNew']) ? $_POST['carNew'] : 0;
$idUserPer= isset($_POST['userPer']) ? $_POST['userPer'] : 0;

$sqlUpdateCarUser = "UPDATE carreras_secretarias set IdCarPer=:idCarPer WHERE  IdCarPer =:idCarPer  AND IdUserPer=:idUserPer ";
$sqlUpdateCarUser = $connect->prepare($sqlUpdateCarUser);
$sqlUpdateCarUser->bindParam(':idCarPer',$idCarPer, PDO::PARAM_INT);
$sqlUpdateCarUser->bindParam(':idUserPer',$idUserPer, PDO::PARAM_INT);

try {
    // Ejecuta la declaración
    $sqlUpdateCarUser->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlUpdateCarUser->rowCount() > 0) {
        echo json_encode(['msessage'=>'Usuario Actualizado']);
    } else {
        echo json_encode(['msessage'=>'Usuario no se pudo Actualizar']);
    }
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $th->getMessage());
    
    echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
}

$connect = null;
?>