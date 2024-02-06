<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$idUserPer = isset($_POST['userPer']) ? $_POST['userPer'] : null;
$idPerPer = isset($_POST['perPer']) ? $_POST['perPer'] : null;
$idItemSubPer = isset($_POST['itemSub']) ? $_POST['itemSub'] : null;

if ($idUserPer != null || $idPerPer != null || $idItemSubPer != null) {

    $exist = false;
    //comprobar que esos datos no existan todavia para evitar duplicidad
    $sql = 'SELECT * FROM usuarios_permisos WHERE IdUserPer=:userPer AND IdPerPer=:perPer AND IdItemSubPer=:itemPer';
    $sql = $connect->prepare($sql);
    $sql->bindParam(':userPer', $idUserPer, PDO::PARAM_INT);
    $sql->bindParam(':perPer', $idPerPer, PDO::PARAM_INT);
    $sql->bindParam(':itemPer', $idItemSubPer, PDO::PARAM_INT);
    try {
        $sql->execute();
        if ($res = $sql->fetch(PDO::FETCH_ASSOC)) {
            $exist = true;
        }

    } catch (\Throwable $th) {
        echo json_encode("Error en la consulta: " . $th->getMessage());
    }

    if ( !$exist ) {
        $sqlInsertPer = "INSERT INTO usuarios_permisos ( IdUserPer, IdPerPer, IdItemSubPer ) VALUES (:userPer, :perPer, :itemPer)";
        $sqlInsertPer = $connect->prepare($sqlInsertPer);
        $sqlInsertPer->bindParam(':userPer', $idUserPer, PDO::PARAM_INT);
        $sqlInsertPer->bindParam(':perPer', $idPerPer, PDO::PARAM_INT);
        $sqlInsertPer->bindParam(':itemPer', $idItemSubPer, PDO::PARAM_INT);

        try {
            $sqlInsertPer->execute();
            // Comprueba si la inserción fue exitosa
            if ($sqlInsertPer->rowCount() > 0) {
                echo json_encode("Permiso Insertado...");
            } else {
                echo json_encode("No se pudo insertar el permiso");
            }
            echo $sqlInsertPer->rowCount();
        } catch (\Throwable $th) {
            echo json_encode("Error en la consulta: " . $th->getMessage());
        }
    }else{
        echo json_encode(["Message" => "Los datos ya existen"]);
    }
} else {
    echo json_encode(["message" => "Necesitas datos para proceder"]);
}
?>