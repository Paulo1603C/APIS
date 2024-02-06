<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$idEst = $_POST['IdEst'];
$nomEst= $_POST['NomEst'];
$apeEst= $_POST['ApeEst'];
$cedEst= $_POST['CedEst'];
$fecha= $_POST['Fecha'];
$idCarPer= $_POST['IdCarPer'];

$sqlUpdateEst = "UPDATE estudiantes set NomEst=:nomEst, ApeEst=:apeEst, CedEst=:cedEst, Fecha=:fecha, IdCarPer=:idCarPer 
                WHERE IdEst=:idEst ";
$sqlUpdateEst = $connect->prepare($sqlUpdateEst);
$sqlUpdateEst->bindParam(':idEst',$idEst, PDO::PARAM_INT);
$sqlUpdateEst->bindParam(':nomEst',$nomEst, PDO::PARAM_STR);
$sqlUpdateEst->bindParam(':apeEst',$apeEst, PDO::PARAM_STR);
$sqlUpdateEst->bindParam(':cedEst',$cedEst, PDO::PARAM_STR);
$sqlUpdateEst->bindParam(':fecha',$fecha, PDO::PARAM_STR);
$sqlUpdateEst->bindParam(':idCarPer',$idCarPer, PDO::PARAM_INT);

try {
    // Ejecuta la declaración
    $sqlUpdateEst->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlUpdateEst->rowCount() > 0) {
        echo json_encode(['msessage'=>'Estudiante Actualizado']);
    } else {
        echo json_encode(['msessage'=>'Estudiante no se pudo Actualizar']);
    }
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $th->getMessage());
    
    echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
}

$connect = null;
?>