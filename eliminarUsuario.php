<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$id = $_POST['id'];
$eliCar = false;
$eliPer = false;

// Para eliminar un usuario primero debemos eliminar las referencias en otras tablas 
$sqlEliminarCarreras = "DELETE FROM carreras_secretarias WHERE IdUserPer = :id";
$sqlEliminarCarreras = $connect->prepare($sqlEliminarCarreras);
$sqlEliminarCarreras->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $sqlEliminarCarreras->execute();
    if ($sqlEliminarCarreras->rowCount() > 0) {
        $eliCar = true;
        //echo "Eliminación de carreras_secretarias exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de carreras_secretarias: " . $e->getMessage());
}

$sqlEliminarPermisos = "DELETE FROM usuarios_permisos WHERE IdUserPer = :id";
$sqlEliminarPermisos = $connect->prepare($sqlEliminarPermisos);
$sqlEliminarPermisos->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $sqlEliminarPermisos->execute();
    if ($sqlEliminarPermisos->rowCount() > 0) {
        $eliPer = true;
        //echo "Eliminación de usuarios_permisos exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de usuarios_permisos: " . $e->getMessage());
}

if ($eliCar == true || $eliPer == true) {
    $sqlEliminar = "DELETE FROM usuarios WHERE IdUser = :id";
    $sqlEliminar = $connect->prepare($sqlEliminar);
    $sqlEliminar->bindParam(':id', $id, PDO::PARAM_INT);

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
