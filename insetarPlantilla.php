<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$nomPlantilla = isset($_POST['NomPlantilla']) ? $_POST['NomPlantilla'] : null;

if ($nomPlantilla != null) {
    $sqlInsertPer = "INSERT INTO plantillas_directorios ( NomPlan ) VALUES (:nomPlan)";
    echo $sqlInsertPer;

    $sqlInsertPer = $connect->prepare($sqlInsertPer);
    $sqlInsertPer->bindParam(':nomPlan', $nomPlantilla, PDO::PARAM_STR);

    try {
        $sqlInsertPer->execute();
        // Comprueba si la inserción fue exitosa
        if ($sqlInsertPer->rowCount() > 0) {
            echo json_encode("Plantilla creada...");
        } else {
            echo json_encode("No se pudo crear la plantilla");
        }
        echo $sqlInsertPer->rowCount();
    } catch (\Throwable $th) {
        echo json_encode("Error en al crear la plantilla: " . $e->getMessage());
    }
}else{
    die('se requiere un nombre para la creación');
}



?>