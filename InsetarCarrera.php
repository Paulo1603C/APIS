<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$nomCarrera = isset($_POST['NomCarrera']) ? $_POST['NomCarrera'] : null;

if ($nomCarrera != null) {
    $sqlInsertPer = "INSERT INTO carreras ( NomCar ) VALUES (:nomCar)";

    $sqlInsertPer = $connect->prepare($sqlInsertPer);
    $sqlInsertPer->bindParam(':nomCar', $nomCarrera, PDO::PARAM_STR);

    try {
        $sqlInsertPer->execute();
        // Comprueba si la inserción fue exitosa
        if ($sqlInsertPer->rowCount() > 0) {
            echo json_encode("Carrera creada...");
        } else {
            echo json_encode("No se pudo crear la Carrera");
        }
        echo $sqlInsertPer->rowCount();
    } catch (\Throwable $th) {
        echo json_encode("Error en al crear la Carrera: " . $e->getMessage());
    }
}else{
    die('se requiere un nombre para la creación');
}



?>