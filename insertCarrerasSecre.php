<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$idUserPer = isset($_POST['userPer']) ? $_POST['userPer'] : 0;
$idCarPer = isset($_POST['carNew']) ? $_POST['carNew'] : 0;

if ($idUserPer == 0) {
    $finalUser = "SELECT IdUser FROM usuarios ORDER BY IdUser DESC LIMIT 1";
    $query = $connect->prepare($finalUser);
    try {
        $query->execute();
        if ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $idUserPer = $result['IdUser'];
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo json_encode("Error en la consulta: " . $th->getMessage());
    }


    $sqlInsertCS = "INSERT INTO carreras_secretarias (IdCarPer, IdUserPer) VALUES (:carPer,:userPer)";

    $sqlInsertCS = $connect->prepare($sqlInsertCS);
    $sqlInsertCS->bindParam(':carPer', $idCarPer, PDO::PARAM_INT);
    $sqlInsertCS->bindParam(':userPer', $idUserPer, PDO::PARAM_INT);


    try {
        $sqlInsertCS->execute();
        // Comprueba si la inserción fue exitosa
        if ($sqlInsertCS->rowCount() > 0) {
            echo json_encode("Insertado Usuario...");
        } else {
            echo json_encode("No se pudo insertar el usuario");
        }
        echo $sqlInsertCS->rowCount();
    } catch (\Throwable $th) {
        echo json_encode("Error en la consulta: " . $th->getMessage());
    }
} else {
    echo json_encode(["message" => "verifica que los datos no seas vacios"]);
}

?>