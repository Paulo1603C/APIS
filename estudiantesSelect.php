<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$IdCar = isset($_POST['IdCar']) ? $_POST['IdCar'] : null;
$IdUser = isset($_POST['IdUser']) ? $_POST['IdUser'] : null;

if ($IdCar != null || $IdUser != null) {
    $list = array();
    $sqlSelect = "SELECT e.IdEst, e.NomEst, e.ApeEst, e.CedEst, e.Fecha, c.IdCar, c.NomCar, u.NomUser, e.Nom_Modificador, e.IdPlanPer
                FROM estudiantes as e
                JOIN Carreras as c ON e.IdCarPer = c.IdCar
                JOIN Carreras_Secretarias as cs ON c.IdCar = cs.IdCarPer
                JOIN Usuarios as u ON cs.IdUserPer = u.IdUser
                WHERE c.IdCar = :idCar
                AND u.IdUser = :idUser
                ORDER BY e.NomEst";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idCar', $IdCar, PDO::PARAM_INT);
    $sqlSelect->bindParam(':idUser', $IdUser, PDO::PARAM_INT);


    try {
        $sqlSelect->execute();
        $result = $sqlSelect->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item) {
            array_push($list, $item);
        }
        echo json_encode($list);
    } catch (\Throwable $th) {
        echo json_encode('Error en la consulta' . $th->getMessage());
    }
} else {
    echo json_encode(['Message' => 'no existen datos para consulta']);
}
?>