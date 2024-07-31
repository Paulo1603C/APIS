<?php
    header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde Vue.js
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
    header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

    require('conexion.php');
    $nomSubSubItem = isset($_POST['nomSubItem']) ? $_POST['nomSubItem'] : null;
    $idItemPlantillaPer = isset($_POST['idItemPlan']) ? $_POST['idItemPlan'] : null;

    if ($nomSubSubItem != null && $idItemPlantillaPer != 0 ) {
        $sqlInsertPer = "INSERT INTO sub_subitems (NomSubSubItem, Id_item_plantilla_per) VALUES (:nomSubSubItem,:idItemPlanPer)";
        //echo $sqlInsertPer;

        $sqlInsertPer = $connect->prepare($sqlInsertPer);
        $sqlInsertPer->bindParam(':nomSubSubItem', $nomSubSubItem, PDO::PARAM_STR);
        $sqlInsertPer->bindParam(':idItemPlanPer', $idItemPlantillaPer, PDO::PARAM_INT);

        try {
            $sqlInsertPer->execute();
            // Comprueba si la inserción fue exitosa
            if ($sqlInsertPer->rowCount() > 0) {
                echo json_encode("SubSubItem creada...");
            } else {
                echo json_encode("No se pudo crear SubSubItem");
            }
            //echo $sqlInsertPer->rowCount();
        } catch (\Throwable $th) {
            echo json_encode("Error en al crear la plantilla: " . $e->getMessage());
        }
    }else{
        die('se requiere un nombre y un id plantilla para ;a creaación para la creación');
    }

?>