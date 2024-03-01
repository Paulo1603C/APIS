<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$idUser = isset($_POST['IdUser']) ? $_POST['IdUser'] : null;

if ($idUser != null) {
    $list = array();
    $sqlSelect = "SELECT u.IdUser, u.NomUser,isd.IdItem, isd.NomItem, p.IdPer,  p.NomPer
                from usuarios_permisos as up JOIN usuarios as u ON up.IdUserPer = u.IdUser
                JOIN permisos as p ON up.IdPerPer = p.IdPer
                JOIN items_subdirectorios as isd ON up.IdItemSubPer = isd.IdItem
                WHERE u.IdUser = :idUser
                ORDER BY isd.NomItem";
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idUser', $idUser, PDO::PARAM_INT);

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
    echo json_encode('Usuario no asigando para la consulta');
}

?>