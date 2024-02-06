<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$IdCarrera = isset($_POST['IdCarrera']) ? $_POST['IdCarrera'] : null;

if( $IdCarrera != null ){
    $sqlEliminar = "DELETE FROM carreras WHERE IdCar = :idCar";
    $sqlEliminar = $connect->prepare($sqlEliminar);
    $sqlEliminar->bindParam(':idCar', $IdCarrera, PDO::PARAM_INT);

    try {
        $sqlEliminar->execute();
        if ($sqlEliminar->rowCount() > 0) {
            echo json_encode(["message" => "Eliminado"]);
        } else {
            echo json_encode(["message" => "No Eliminado"]);
        }
    } catch (PDOException $e) {
        echo json_encode("Error en la sentecia sql: " . $e->getMessage());
    }
} else {
    echo json_encode(["message" => "Error al eliminar la carrera"]);
}

$connect = null;

?>
