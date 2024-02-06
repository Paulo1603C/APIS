<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$list = array();
$sqlSelect = " SELECT IdItem, NomItem FROM items_subdirectorios ";
$query = $connect->prepare($sqlSelect);

try {
    $query -> execute();
    $result = $query -> fetchAll(PDO::FETCH_ASSOC);
    foreach( $result as $item ){
        array_push($list, $item);
    } 
    echo json_encode($list);
} catch (\Throwable $th) {
    echo json_encode('Error en la consulta'. $th->getMessage());
}


?>