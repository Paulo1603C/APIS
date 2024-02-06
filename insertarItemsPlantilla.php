<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$nomSubDirectorio = isset($_POST['NomSubDirectorio']) ? $_POST['NomSubDirectorio'] : null;
$nomAux = '';

if ($nomSubDirectorio != null) {

    
    //evitar directorios repetidos
    $select = "SELECT NomItem FROM items_plantillas WHERE NomItem=:nomPlan";
    $select = $connect->prepare($select);
    $select->bindParam(':nomPlan', $nomSubDirectorio, PDO::PARAM_STR);
    try {
        $select->execute();
        $result = $select->fetch(PDO::FETCH_COLUMN);
        if ($result !== false) {
            $nomAux = $result;
        } else {
            echo "No se encontró ningún registro con el nombre $nomSubDirectorio";
        }
    } catch (\Throwable $th) {
        echo "Error: " . $th->getMessage();
    }

    if ($nomAux == '') {
        $sqlInsertPer = "INSERT INTO items_plantillas ( NomItem ) VALUES (:nomPlan)";
        $sqlInsertPer = $connect->prepare($sqlInsertPer);
        $sqlInsertPer->bindParam(':nomPlan', $nomSubDirectorio, PDO::PARAM_STR);

        try {
            $sqlInsertPer->execute();
            // Comprueba si la inserción fue exitosa
            if ($sqlInsertPer->rowCount() > 0) {
                echo json_encode("SubDirectorio creado...");
            } else {
                echo json_encode("No se pudo crear el SubDirectorio");
            }
            echo $sqlInsertPer->rowCount();
        } catch (\Throwable $th) {
            echo json_encode("Error en al crear la plantilla: " . $e->getMessage());
        }

    }

} else {
    die('se requiere un nombre para la creación');
}



?>