<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$id = $_POST['id'];
$eliItems = false;
$eliEst = false;

// Para eliminar un usuario primero debemos eliminar las referencias en otras tablas 
$sqlEliminarItemsPlan = "DELETE FROM items_Plantillas WHERE IdPlanPer = :id";
$sqlEliminarItemsPlan = $connect->prepare($sqlEliminarItemsPlan);
$sqlEliminarItemsPlan->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $sqlEliminarItemsPlan->execute();
    if ($sqlEliminarItemsPlan->rowCount() > 0) {
        $eliItems = true;
        //echo "Eliminación de carreras_secretarias exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de carreras_secretarias: " . $e->getMessage());
}

$sqlEliminarPermisos = "DELETE FROM estudiantes WHERE IdPlanPer = :id";
$sqlEliminarPermisos = $connect->prepare($sqlEliminarPermisos);
$sqlEliminarPermisos->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $sqlEliminarPermisos->execute();
    if ($sqlEliminarPermisos->rowCount() > 0) {
        $eliEst = true;
        //echo "Eliminación de usuarios_permisos exitosa<br>";
    }
} catch (PDOException $e) {
    echo json_encode("Error en la consulta de usuarios_permisos: " . $e->getMessage());
}

if ($eliItems == true || $eliEst == true) {
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
