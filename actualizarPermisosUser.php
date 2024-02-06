<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$idPerPer = $_POST['perPer'];
$idUserPer= $_POST['userPer'];

$sqlUpdatePerUser = "UPDATE usuarios_permisos set IdPerPer=:idPerPer WHERE IdUserPer=:idUserPer ";
$sqlUpdatePerUser = $connect->prepare($sqlUpdatePerUser);
$sqlUpdatePerUser->bindParam(':idPerPer',$idPerPer, PDO::PARAM_INT);
$sqlUpdatePerUser->bindParam(':idUserPer',$idUserPer, PDO::PARAM_INT);

try {
    // Ejecuta la declaración
    $sqlUpdatePerUser->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlUpdatePerUser->rowCount() > 0) {
        echo json_encode(['msessage'=>'Usuario Actualizado']);
    } else {
        echo json_encode(['msessage'=>'Usuario no se pudo Actualizar']);
    }
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    
    echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
}

$connect = null;
?>