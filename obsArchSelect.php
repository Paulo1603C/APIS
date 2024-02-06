<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$RutaArchivo = isset($_POST['rutaObs']) ? $_POST['rutaObs'] : null;

if ($RutaArchivo != null) {
    $list = array();
    $sqlSelect = " SELECT Observacion FROM archivos_estudiantes where RutaArch=:rutaObs";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(":rutaObs", $RutaArchivo, PDO::PARAM_STR);

    try {
        $sqlSelect->execute();
        $result = $sqlSelect->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item) {
            array_push($list, $item);
        }
        echo json_encode($list);
    } catch (\Throwable $th) {
        echo json_encode('Error en la consulta' . $th->getMessage());
    }

} else {
    echo json_encode(['message' => 'Necesitas datos']);
}



?>