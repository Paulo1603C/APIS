<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$idCar = isset($_POST['idCar']) ? $_POST['idCar'] : 0;

if ($id != 0 || $idCar != 0 ) {
    $sqlEliminar = "DELETE FROM carreras_secretarias WHERE IdCarPer = :idCar  AND  IdUserPer = :id";
    $sqlEliminar = $connect->prepare($sqlEliminar);
    $sqlEliminar->bindParam(':id', $id, PDO::PARAM_INT);
    $sqlEliminar->bindParam(':idCar', $idCar, PDO::PARAM_INT);

    try {
        $sqlEliminar->execute();
        if ($sqlEliminar->rowCount() > 0) {
            echo json_encode(["message" => "Eliminado"]);
        } else {
            echo json_encode(["message" => "No Eliminado"]);
        }
    } catch (PDOException $e) {
        echo json_encode("Error en la consulta de usuarios: " . $e->getMessage());
    }
} else {
    echo json_encode(["message" => "Error al eliminar el Usuario"]);
}

$connect = null;
?>