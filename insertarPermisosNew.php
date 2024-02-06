<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$idUserPer = isset($_POST['userPer']) ? $_POST['userPer'] : 0;
$idPerPer = isset($_POST['perPer']) ? $_POST['perPer'] : 0;
$nomItemSubPer = isset($_POST['itemSub']) ? $_POST['itemSub'] : null;

if ($idUserPer != 0 || $idPerPer != 0 || $nomItemSubPer != null) {

    $idItemSubPer=0;
    //comprobar que esos datos no existan todavia para evitar duplicidad
    $sql = 'SELECT IdItem FROM items_subdirectorios WHERE NomItem=:itemPer ';
    $sql = $connect->prepare($sql);
    $sql->bindParam(':itemPer', $nomItemSubPer, PDO::PARAM_STR);
    try {
        $sql->execute();
        if ($res = $sql->fetch(PDO::FETCH_ASSOC)) {
            $idItemSubPer=$res['IdItem'];
        }

    } catch (\Throwable $th) {
        echo json_encode("Error en la consulta: " . $th->getMessage());
    }

    if ($idItemSubPer != 0) {
        $sqlInsertPer = "INSERT INTO usuarios_permisos ( IdUserPer, IdPerPer, IdItemSubPer ) VALUES (:userPer, :perPer, :idItemPer)";
        $sqlInsertPer = $connect->prepare($sqlInsertPer);
        $sqlInsertPer->bindParam(':userPer', $idUserPer, PDO::PARAM_INT);
        $sqlInsertPer->bindParam(':perPer', $idPerPer, PDO::PARAM_INT);
        $sqlInsertPer->bindParam(':idItemPer', $idItemSubPer, PDO::PARAM_INT);

        try {
            $sqlInsertPer->execute();
            // Comprueba si la inserción fue exitosa
            if ($sqlInsertPer->rowCount() > 0) {
                echo json_encode("Permiso Insertado...");
            } else {
                echo json_encode("No se pudo insertar el permiso");
            }
        } catch (\Throwable $th) {
            echo json_encode("Error en la consulta: " . $th->getMessage());
        }
    } else {
        echo json_encode(["Message" => "Los datos ya existen"]);
    }
} else {
    echo json_encode(["message" => "Necesitas datos para proceder"]);
}
?>