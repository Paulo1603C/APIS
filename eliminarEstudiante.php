<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$idEst = $_POST['idEst'];

// Para eliminar un usuario primero debemos eliminar las referencias en otras tablas 
$sqlEliminarEstDirectorio = "DELETE FROM ARCHIVOS_ESTUDIANTES WHERE IdEstPer = :id";
$sqlEliminarEstDirectorio = $connect->prepare($sqlEliminarEstDirectorio);
$sqlEliminarEstDirectorio->bindParam(':id', $idEst, PDO::PARAM_INT);

try {
    $sqlEliminarEstDirectorio->execute();
    if ($sqlEliminarEstDirectorio->rowCount() > 0) {
        //echo "Eliminación de carreras_secretarias exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de carreras_secretarias: " . $e->getMessage());
}

$sqlEliminar = "DELETE FROM estudiantes WHERE IdEst = :id";
$sqlEliminar = $connect->prepare($sqlEliminar);
$sqlEliminar->bindParam(':id', $idEst, PDO::PARAM_INT);

try {
    $sqlEliminar->execute();
    if ($sqlEliminar->rowCount() > 0) {
        echo json_encode(["message" => "Estudiante Eliminado"]);
    } else {
        echo json_encode(["message" => "No Eliminado"]);
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de usuarios: " . $e->getMessage());
}

$connect = null;
?>
