<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$list = array();
$idPlantilla = isset($_POST['idPlantilla']) ? $_POST['idPlantilla'] : 0;

if ($idPlantilla == 0) {
    $sqlSelect = "SELECT PD.IdPlan, PD.NomPlan, GROUP_CONCAT(ISD.IdItem) AS IdItem,GROUP_CONCAT(ISD.NomItem) AS Items
    FROM plantillas_directorios PD
    JOIN items_directorios IDIR ON PD.IdPlan = IDIR.IdPlanPer
    JOIN items_plantillas ISD ON IDIR.IdSubDirPer = ISD.IdItem
    GROUP BY PD.IdPlan
    ORDER BY PD.NomPlan";
} else {
    $sqlSelect = "SELECT PD.IdPlan, PD.NomPlan, GROUP_CONCAT(ISD.IdItem) AS IdItem,GROUP_CONCAT(ISD.NomItem) AS items
    FROM plantillas_directorios PD
    JOIN items_directorios IDIR ON PD.IdPlan = IDIR.IdPlanPer
    JOIN items_plantillas ISD ON IDIR.IdSubDirPer = ISD.IdItem
    WHERE PD.IdPlan = :idPlantilla
    GROUP BY PD.IdPlan;
    ORDER BY PD.NomPlan";
}
$sqlSelect = $connect->prepare($sqlSelect);
$sqlSelect->bindParam(':idPlantilla',$idPlantilla,PDO::PARAM_STR);


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

?>