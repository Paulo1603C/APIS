<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require('conexion.php');

$idUser = isset($_POST['IdUser']) ? $_POST['IdUser'] : null;
$nomItem = isset($_POST['NomItem']) ? $_POST['NomItem'] : null;

if ($idUser != null || $nomItem != null) {
    $list = array();
    if (is_numeric($nomItem)) {
        $sqlSelect = "SELECT  GROUP_CONCAT(DISTINCT u.IdUser) AS IdUser , GROUP_CONCAT(DISTINCT u.NomUser) AS NomUser, 
                    GROUP_CONCAT(DISTINCT isd.IdItem) as IdItem , GROUP_CONCAT(DISTINCT isd.NomItem) as NomItem, 
                    GROUP_CONCAT( p.IdPer) as IdPer , GROUP_CONCAT( p.NomPer) as NomPer
                    from usuarios_permisos as up JOIN usuarios as u ON up.IdUserPer = u.IdUser
                    JOIN permisos as p ON up.IdPerPer = p.IdPer
                    JOIN items_subdirectorios as isd ON up.IdItemSubPer = isd.IdItem
                    WHERE u.IdUser = :idUser
                    AND isd.IdItem = :nomItem; 
                    ORDER BY u.NomUser";
    } else {
        $sqlSelect = "SELECT  GROUP_CONCAT(DISTINCT u.IdUser) AS IdUser , GROUP_CONCAT(DISTINCT u.NomUser) AS NomUser, 
                    GROUP_CONCAT(DISTINCT isd.IdItem) as IdItem , GROUP_CONCAT(DISTINCT isd.NomItem) as NomItem, 
                    GROUP_CONCAT( p.IdPer) as IdPer , GROUP_CONCAT( p.NomPer) as NomPer
                    from usuarios_permisos as up JOIN usuarios as u ON up.IdUserPer = u.IdUser
                    JOIN permisos as p ON up.IdPerPer = p.IdPer
                    JOIN items_subdirectorios as isd ON up.IdItemSubPer = isd.IdItem
                    WHERE u.IdUser = :idUser
                    AND isd.NomItem = :nomItem; ";
    }
    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $sqlSelect->bindParam(':nomItem', $nomItem, PDO::PARAM_STR);

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