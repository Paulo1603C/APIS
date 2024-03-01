<?php

header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos

require ('conexion.php');

$user = isset($_POST['user']) ? $_POST['user']: null;
$pass = isset($_POST['pass'])? $_POST['pass']: null;

$sqlSelect = "SELECT IdUser, NomUser, ApeUser, IdRolPer, Correo, Contraseña FROM usuarios where Correo= :user AND Contraseña= :pass";
$sqlSelect = $connect -> prepare($sqlSelect);
$sqlSelect->bindParam(':user', $user,PDO::PARAM_STR);
$sqlSelect->bindParam(':pass', $pass,PDO::PARAM_STR);

try {
    if( $user != '' || $pass != '' ){
        $sqlSelect->execute(); 
        $result = $sqlSelect->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result); 
    }else{
        echo json_encode(['Message' => 'No existen credenciales para la consulta de login']);
    }
} catch (\Throwable $th) {
    echo json_encode('Error en la consulta'. $th->getMessage());
}
?>