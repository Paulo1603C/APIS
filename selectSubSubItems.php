<?php

    header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
    header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

    require ('conexion.php');

    $idItemPlantillaPer = isset($_POST['idItemPlan']) ? $_POST['idItemPlan'] : null;
    $listSubItems = array();

    if( $idItemPlantillaPer != 0 ){
        $select = "SELECT * FROM sub_subitems WHERE Id_item_plantilla_per = :idItemPlan";
        $select = $connect->prepare($select);
        $select->bindParam(':idItemPlan', $idItemPlantillaPer, PDO::PARAM_INT);

        try {
            $select->execute();
            $result = $select->fetchAll(PDO::FETCH_ASSOC);
            $listCarreras = $result; 
            echo json_encode($listCarreras);
        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }

    }else{
        echo json_encode(array("error" => "ID de item de plantilla no proporcionado o es inválido."));
    }

?>