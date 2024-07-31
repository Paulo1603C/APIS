<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$list = array();
$idPlantilla = isset($_POST['idPlantilla']) ? $_POST['idPlantilla'] : 0;

$sqlSelect = "SELECT PD.IdPlan, PD.NomPlan, GROUP_CONCAT(ISD.IdItem) AS IdItem,GROUP_CONCAT(ISD.NomItem) AS Items
FROM plantillas_directorios PD
JOIN items_directorios as IDIR ON PD.IdPlan = IDIR.IdPlanPer
JOIN items_plantillas as ISD ON IDIR.IdSubDirPer = ISD.IdItem";

if ($idPlantilla != 0) {
    $sqlSelect .= " WHERE PD.IdPlan = :idPlantilla";
}

$sqlSelect .= " GROUP BY PD.IdPlan ORDER BY PD.NomPlan";

$sqlSelect = $connect->prepare($sqlSelect);

if ($idPlantilla != 0) {
    $sqlSelect->bindParam(':idPlantilla', $idPlantilla, PDO::PARAM_INT);
}

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
