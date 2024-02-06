<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$listCarreras = array();
$select = "SELECT IdRol, NomRol FROM roles";
$query = $connect->prepare($select);
try {
    $query->execute();
    $result = $query-> fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $item ){
        array_push($listCarreras, $item);
    }
    echo json_encode($listCarreras);
} catch (\Throwable $th) {
    //throw $th;
    echo "Error".$th;
}

?>