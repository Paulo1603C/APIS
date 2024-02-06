<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$RutaArchivo = isset($_POST['RutaArchivo']) ? $_POST['RutaArchivo'] : null;
$Observacion = isset($_POST['Observacion']) ? $_POST['Observacion'] : null;
$IdEstPer = isset($_POST['IdEstPer']) ? $_POST['IdEstPer'] : 0;

if ($RutaArchivo != null  && $Observacion != null  && $IdEstPer != 0) {
    $sqlInsertObsArch = "INSERT INTO archivos_estudiantes( RutaArch, Observacion, IdEstPer) 
                    VALUES  (:rutaArch, :observacion, :idEstPer)";
    //echo $sqlInsertObsArch;

    $sqlInsertObsArch = $connect->prepare($sqlInsertObsArch);
    $sqlInsertObsArch->bindParam(':rutaArch', $RutaArchivo, PDO::PARAM_STR);
    $sqlInsertObsArch->bindParam(':observacion', $Observacion, PDO::PARAM_STR);
    $sqlInsertObsArch->bindParam(':idEstPer', $IdEstPer, PDO::PARAM_INT);

    try {
        $sqlInsertObsArch->execute();
        // Comprueba si la inserción fue exitosa
        if ($sqlInsertObsArch->rowCount() > 0) {
            echo json_encode("Observacion insertada creada...");
        } else {
            echo json_encode("No se pudo crear la observacion");
        }
    } catch (\Throwable $th) {
        echo json_encode("Error en al crear la observacion: " . $e->getMessage());
    }
}else{
    die('se requiere un nombre para la creación');
}



?>