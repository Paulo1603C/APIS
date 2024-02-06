<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$idCarrera = isset($_POST['idCarrera']) ? $_POST['idCarrera'] : null;
$nomCarrera = isset($_POST['NomCarrera']) ? $_POST['NomCarrera'] : null;

if ($nomCarrera != null) {
    $sqlInsertPer = "UPDATE carreras set NomCar=:nomCar WHERE IdCar=:idCar";

    $sqlInsertPer = $connect->prepare($sqlInsertPer);
    $sqlInsertPer->bindParam(':idCar', $idCarrera, PDO::PARAM_INT);
    $sqlInsertPer->bindParam(':nomCar', $nomCarrera, PDO::PARAM_STR);

    try {
        $sqlInsertPer->execute();
        // Comprueba si la inserción fue exitosa
        if ($sqlInsertPer->rowCount() > 0) {
            echo json_encode("Carrera actualizada...");
        } else {
            echo json_encode("No se pudo actualizar la Carrera");
        }
    } catch (\Throwable $th) {
        echo json_encode("Error en al actualizar la Carrera: " . $e->getMessage());
    }
}else{
    die('se requiere un nombre para la actualizacion');
}


?>