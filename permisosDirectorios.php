<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require('conexion.php');

// Validar entradas
$idUser = isset($_POST['IdUser']) ? intval($_POST['IdUser']) : null;
$nomItem = isset($_POST['NomItem']) ? trim($_POST['NomItem']) : null;

if ($idUser !== null && $nomItem !== null && $idUser > 0 && $nomItem !== '') {
    $list = array();

    // Construcción de la consulta
    $sqlSelect = "SELECT 
                    GROUP_CONCAT(DISTINCT u.IdUser) AS IdUser, 
                    GROUP_CONCAT(DISTINCT u.NomUser) AS NomUser, 
                    GROUP_CONCAT(DISTINCT isd.IdItem) as IdItem, 
                    GROUP_CONCAT(DISTINCT isd.NomItem) as NomItem, 
                    GROUP_CONCAT(p.IdPer) as IdPer, 
                    GROUP_CONCAT(p.NomPer) as NomPer
                  FROM usuarios_permisos as up 
                  JOIN usuarios as u ON up.IdUserPer = u.IdUser
                  JOIN permisos as p ON up.IdPerPer = p.IdPer
                  JOIN items_subdirectorios as isd ON up.IdItemSubPer = isd.IdItem
                  WHERE u.IdUser = :idUser
                  AND (isd.IdItem = :nomItem OR isd.NomItem = :nomItem)
                  ORDER BY u.NomUser";

    $sqlSelect = $connect->prepare($sqlSelect);
    $sqlSelect->bindParam(':idUser', $idUser, PDO::PARAM_INT);

    // Determinar si nomItem es numérico o una cadena
    if (is_numeric($nomItem)) {
        $sqlSelect->bindParam(':nomItem', $nomItem, PDO::PARAM_INT);
    } else {
        $sqlSelect->bindParam(':nomItem', $nomItem, PDO::PARAM_STR);
    }

    try {
        $sqlSelect->execute();
        $result = $sqlSelect->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['mensaje' => 'No se encontraron resultados']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parámetros inválidos o incompletos']);
}

?>
