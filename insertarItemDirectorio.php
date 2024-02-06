<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$idPlanPer = 0;
$idSubDirPer = 0;
$nomItem = isset($_POST['NomItem']) ? $_POST['NomItem'] : null;
$idPlan = isset($_POST['IdPlan']) ? $_POST['IdPlan'] : 0;
$controlPlan = false;
$controlSubDir = false;

if ($nomItem != null) {
    if ( $idPlan == 0 ) {
        $finalUser = "SELECT IdPlan FROM plantillas_directorios ORDER BY IdPlan DESC LIMIT 1";
        $finalUser = $connect->prepare($finalUser);
        try {
            $finalUser->execute();
            if ($result = $finalUser->fetch(PDO::FETCH_ASSOC)) {
                $idPlanPer = $result['IdPlan'];
                $controlPlan = true;
            }
        } catch (\Throwable $e) {
            echo json_encode("Error en la consulta: " . $e->getMessage());
        }
    }else{
        $idPlanPer = $idPlan;
    }

    if ( $idSubDirPer == 0 ) {
        $idItemSelect = "SELECT IdItem FROM items_plantillas WHERE NomItem=:nomItem";
        $idItemSelect = $connect->prepare($idItemSelect);
        $idItemSelect->bindParam(":nomItem", $nomItem, PDO::PARAM_STR);
        try {
            $idItemSelect->execute();
            if ($result2 = $idItemSelect->fetch(PDO::FETCH_ASSOC)) {
                $idSubDirPer = $result2['IdItem'];
                $controlSubDir = true;
            }
        } catch (\Throwable $e) {
            echo json_encode("Error en la consulta: " . $e->getMessage());
        }
    }

    if( $controlPlan==true || $controlSubDir == true ){
        $sqlInsertPer = "INSERT INTO items_directorios ( IdPlanPer, IdSubDirPer ) VALUES ( :idPlanPer, :idSubDirPer )";
        //echo $sqlInsertPer;
        $sqlInsertPer = $connect->prepare($sqlInsertPer);
        $sqlInsertPer->bindParam(':idPlanPer', $idPlanPer, PDO::PARAM_INT);
        $sqlInsertPer->bindParam(':idSubDirPer', $idSubDirPer, PDO::PARAM_INT);

        try {
            $sqlInsertPer->execute();
            // Comprueba si la inserción fue exitosa
            if ($sqlInsertPer->rowCount() > 0) {
                echo json_encode(" Item Insertado...");
            } else {
                echo json_encode("No se pudo insertar el Item");
            }
        } catch (\Throwable $th) {
            echo json_encode("Error en la consulta: " . $e->getMessage());
        }
    }

} else {
    die('se requiere un nombre para la creación');
}

?>