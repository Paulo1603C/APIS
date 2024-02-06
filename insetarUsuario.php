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

$sqlInsertUser = "INSERT INTO usuarios (IdUser, NomUser, ApeUser, Contraseña, IdRolPer, Correo) 
                VALUES ($id, '$nombre', '$apellido', '$contraseña', $idRolPer, '$correo')";

$sqlInsertUser = $connect->prepare($sqlInsertUser);
/*$sqlInsertUser->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':apellido', $apellido, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':contraseña', $contraseña, PDO::PARAM_STR);
$sqlInsertUser->bindParam(':idRolPer', $idRolPer, PDO::PARAM_INT);
$sqlInsertUser->bindParam(':correo', $correo, PDO::PARAM_STR);*/

try {
    // Ejecuta la declaración
    $sqlInsertUser->execute();

    // Comprueba si la inserción fue exitosa
    if ($sqlInsertUser->rowCount() > 0) {
        echo json_encode("Insertado Usuario...");
    } else {
        echo json_encode("No se pudo insertar el usuario");
    }
    echo $sqlInsertUser->rowCount();
} catch (PDOException $e) {
    echo json_encode("Error en la consulta: " . $e->getMessage());
}
?>