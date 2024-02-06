<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$nomItem = $_POST['nomItem'];
$nuevoItem= $_POST['nuevoItem'];

$sqlUpdateItem = "UPDATE items_plantillas set NomItem=:nuevoItem WHERE NomItem=:nomItem ";
$sqlUpdateItem = $connect->prepare($sqlUpdateItem);
$sqlUpdateItem->bindParam(':nomItem',$nomItem, PDO::PARAM_STR);
$sqlUpdateItem->bindParam(':nuevoItem',$nuevoItem, PDO::PARAM_STR);

try {
    // Ejecuta la declaración
    $sqlUpdateItem->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlUpdateItem->rowCount() > 0) {
        echo json_encode(['message'=>'SubCarpeta Actualizado']);
    } else {
        echo json_encode(['message'=>'la subCarpeta no se pudo Actualizar']);
    }
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
}

$connect = null;
?>