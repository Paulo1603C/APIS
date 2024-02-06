<?php
header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde tu aplicación Vue.js
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define los métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define los encabezados permitidos
require('conexion.php');
    
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$contraseña = $_POST['contraseña'];
$idRolPer = $_POST['idRol'];
$correo = $_POST['correo'];

$sqlUpdatetUser = "UPDATE usuarios SET NomUser= '$nombre', ApeUser= '$apellido', Contraseña= '$contraseña', 
                    IdRolPer= $idRolPer, Correo= '$correo' WHERE IdUser= $id";
$sqlUpdatetUser = $connect->prepare($sqlUpdatetUser);
/*$sqlInsertUser->bindParam(':id', $id, PDO::PARAM_INT);
/*$sqlInsertUser->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':apellido', $apellido, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':contraseña', $contraseña, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':idRolPer', $idRolPer, PDO::PARAM_INT);
$sqlInsertUser->bindParam(':correo', $correo, PDO::PARAM_STR);*/

try {
    // Ejecuta la declaración
    $sqlUpdatetUser->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlUpdatetUser->rowCount() > 0) {
        echo json_encode(['msessage'=>'Usuario Actualizado']);
    } else {
        echo json_encode(['msessage'=>'Usuario no se pudo Actualizar']);
    }
    
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    
    echo json_encode(["message" => "Error en la consulta. Por favor, intenta de nuevo más tarde."]);
}
?>