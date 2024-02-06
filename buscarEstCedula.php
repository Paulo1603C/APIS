<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$cedEst = isset($_POST['cedEst']) ? $_POST['cedEst'] : null;
if ($cedEst != null) {
    $sqlSelect = "SELECT * FROM estudiantes as e WHERE e.CedEst =:cedEst ";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':cedEst', $cedEst, PDO::PARAM_STR);


    try {
        $sqlSelect->execute();
        $result = $sqlSelect->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo json_encode('existe');
        }else{
            echo json_encode('no existe el estudiante');
        }
    } catch (\Throwable $th) {
        echo json_encode('Error en la consulta' . $th->getMessage());
    }
}else{
    echo json_encode(['Message' => 'No hay datos']);
}
?>