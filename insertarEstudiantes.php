<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');
$nomEst= $_POST['NomEst'];
$apeEst= $_POST['ApeEst'];
$cedEst= $_POST['CedEst'];
$fecha= $_POST['Fecha'];
$nomModificador = $_POST['NomModificador'];
$idCarPer= $_POST['IdCarPer'];
$idPlanPer= $_POST['IdPlanPer'];

$sqlInsertEst="INSERT INTO estudiantes (NomEst, ApeEst, CedEst, Fecha, Nom_Modificador, IdCarPer, IdPlanPer) 
                        VALUES (:nomEst, :apeEst, :cedEst, :fecha, :nomModificador, :idCarPer, :idPlanPer)";
$sqlInsertEst = $connect->prepare($sqlInsertEst);
$sqlInsertEst->bindParam(':nomEst',$nomEst, PDO::PARAM_STR);
$sqlInsertEst->bindParam(':apeEst',$apeEst, PDO::PARAM_STR);
$sqlInsertEst->bindParam(':cedEst',$cedEst, PDO::PARAM_STR);
$sqlInsertEst->bindParam(':fecha',$fecha, PDO::PARAM_STR);
$sqlInsertEst->bindParam(':nomModificador',$nomModificador, PDO::PARAM_STR);
$sqlInsertEst->bindParam(':idCarPer',$idCarPer, PDO::PARAM_INT);
$sqlInsertEst->bindParam(':idPlanPer',$idPlanPer, PDO::PARAM_INT);


try {
    $sqlInsertEst->execute();
    // Comprueba si la inserción fue exitosa
    if ($sqlInsertEst->rowCount() > 0) {
        echo json_encode("Insertado Usuario...");
    } else {
        echo json_encode("No se pudo insertar el usuario");
    }
    echo $sqlInsertEst->rowCount();
} catch (\Throwable $th) {
    echo json_encode("Error en la consulta: " . $th->getMessage());
}

?>