<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$idEst = isset($_POST['IdEst'])? $_POST['IdEst'] : 0;
$fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : null;

if( $idEst != 0 && $fecha != null ){
    $sqlUpdateItem = "UPDATE estudiantes set Fecha=:fecha WHERE IdEst=:IdEst ";
    $sqlUpdateItem = $connect->prepare($sqlUpdateItem);
    $sqlUpdateItem->bindParam(':fecha',$fecha, PDO::PARAM_STR);
    $sqlUpdateItem->bindParam(':IdEst',$idEst, PDO::PARAM_INT);

    try {
        // Ejecuta la declaración
        $sqlUpdateItem->execute();

        // Comprueba si la inserción fue exitosa
        if ($sqlUpdateItem->rowCount() > 0) {
            echo json_encode(['message'=>'Estudiante Actualizado']);
        } else {
            echo json_encode(['message'=>'El estudiante no se pudo Actualizar']);
        }
    } catch (PDOException $e) {
        error_log("Error en la consulta: " . $e->getMessage());
        echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
    }
    
}

$connect = null;
?>