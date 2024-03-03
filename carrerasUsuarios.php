<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$idUser=$_POST['IdUser'];
$listCarreras = array();
$selectCarreras = "SELECT DISTINCT c.idCar, c.nomCar 
            FROM carreras as c
            JOIN carreras_secretarias as cs ON cs.IdCarPer = c.IdCar
            JOIN usuarios as u ON u.IdUser = cs.IdUserPer
            where IdUser = :IdUser
            ORDER BY c.nomCar";
$selectCarreras = $connect->prepare($selectCarreras);
$selectCarreras->bindParam(':IdUser',$idUser, PDO::PARAM_INT);

try {
    $selectCarreras->execute();
    $result = $selectCarreras-> fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $item ){
        array_push($listCarreras, $item);
    }
    echo json_encode($listCarreras);
} catch (\Throwable $th) {
    echo json_encode("Error".$th);
}

?>