<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require('conexion.php');

$idUserPer = isset($_POST['userPer']) ? $_POST['userPer'] : null;
$idPerPer = isset($_POST['perPer']) ? $_POST['perPer'] : null;
$idItemSubPer = isset($_POST['itemSub']) ? $_POST['itemSub'] : null;

if ( $idUserPer != null || $idPerPer != null || $idItemSubPer != null ) {
    $sqlPermisosSubUser = "DELETE FROM usuarios_permisos WHERE IdUserPer=:userPer AND IdPerPer=:perPer AND IdItemSubPer=:itemPer";
    $sqlPermisosSubUser = $connect->prepare($sqlPermisosSubUser);
    $sqlPermisosSubUser->bindParam(':userPer', $idUserPer, PDO::PARAM_INT);
    $sqlPermisosSubUser->bindParam(':perPer', $idPerPer, PDO::PARAM_INT);
    $sqlPermisosSubUser->bindParam(':itemPer', $idItemSubPer, PDO::PARAM_INT);

    try {
        $sqlPermisosSubUser->execute();
        if ($sqlPermisosSubUser->rowCount() > 0) {
            echo json_encode(["message" => "Eliminado"]);
        } else {
            echo json_encode(["message" => "No Eliminado"]);
        }
    } catch (PDOException $e) {
        echo json_encode("Error en la consulta de carreras_secretarias: " . $e->getMessage());
    }
}else{
    echo json_encode(["message" => "No existen datos para la eliminación"]);
    
}

$connect = null;
?>