<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$IdPlanPer = isset($_POST['IdPlanPer']) ? $_POST['IdPlanPer'] : null;

if ($IdPlanPer != null) {
    $list = array();
    $sqlSelect = "SELECT e.NomEst, e.ApeEst, c.NomCar
                    FROM estudiantes as e JOIN carreras as c ON e.IdCarPer=c.IdCar
                    WHERE IdPlanPer  = :idPlanPer
                    ORDER BY e.NomEst";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idPlanPer', $IdPlanPer, PDO::PARAM_INT);


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
    echo json_encode(['Message' => 'no existen datos para consulta']);
}
?>