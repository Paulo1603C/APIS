<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$idPlantilla = $_POST['IdPlantilla'];
$eliItems = false;

//eliminar de tablas referenciales 
$sqlEliminarItemsPlan = "DELETE FROM items_directorios where idPlanPer =:idPlan";
$sqlEliminarItemsPlan = $connect->prepare($sqlEliminarItemsPlan);
$sqlEliminarItemsPlan->bindParam(':idPlan', $idPlantilla, PDO::PARAM_INT);

try {
    $sqlEliminarItemsPlan->execute();
    if ($sqlEliminarItemsPlan->rowCount() > 0) {
        $eliItems = true;
        //echo "Eliminación de carreras_secretarias exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de carreras_secretarias: " . $e->getMessage());
}


if ( $eliItems == true ) {
    $sqlEliminarPlan = "DELETE FROM plantillas_directorios where idPlan =:idPlan";
    $sqlEliminarPlan = $connect->prepare($sqlEliminarPlan);
    $sqlEliminarPlan->bindParam(':idPlan', $idPlantilla, PDO::PARAM_INT);

    try {
        $sqlEliminarPlan->execute();
        if ($sqlEliminarPlan->rowCount() > 0) {
            echo json_encode(["message" => "Eliminado"]);
        } else {
            echo json_encode(["message" => "No Eliminado"]);
        }
    } catch (PDOException $e) {
        echo json_encode("Error en la consulta de Plantilla: " . $e->getMessage());
    }
} else {
    echo json_encode(["message" => "Error al eliminar la Plantilla"]);
}
    $connect = null;
?>
