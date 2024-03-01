<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$idPlan = isset($_POST['IdPlan']) ? $_POST['IdPlan'] : null;
if ($idPlan != null) {
    $list = array();
    $sqlSelect = " SELECT ip.NomItem 
                FROM `items_directorios` AS id 
                JOIN `items_plantillas` AS ip ON id.IdSubDirPer = ip.IdItem
                WHERE id.IdPlanPer = :idPlan ";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idPlan',$idPlan, PDO::PARAM_INT);

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
}else{
    echo json_encode(['message' => 'Se necesita un dato para buscar']);
}

?>