<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$list = array();
$sqlSelect = "SELECT
                u.IdUser,u.NomUser,u.ApeUser,u.Contraseña, u.Correo,
                GROUP_CONCAT(DISTINCT c.IdCar)  AS IdCarreras, GROUP_CONCAT(DISTINCT c.NomCar)  AS Carreras,
                r.IdRol, r.NomRol,
                GROUP_CONCAT(DISTINCT p.IdPer) AS IdPermisos, GROUP_CONCAT(DISTINCT p.nomPer) AS Permisos
                FROM
                usuarios AS u
                JOIN
                carreras_secretarias AS cs ON u.idUser = cs.idUserPer
                JOIN
                carreras AS c ON cs.idCarPer = c.IdCar
                JOIN
                roles AS r ON u.IdRolPer = r.IdRol
                LEFT JOIN
                usuarios_permisos AS up ON u.idUser = up.idUserPer
                LEFT JOIN
                permisos AS p ON up.idPerPer = p.idPer
                GROUP BY
                u.IdUser, u.NomUser, u.ApeUser, u.Correo, r.NomRol
                ORDER BY
                u.NomUser
                ";
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