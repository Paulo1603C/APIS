<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

$sftp = new SFTP($servidor, $puerto);

try {
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    }

    $nombreDirectorio = isset($_POST['nombreDirectorio']) ? $_POST['nombreDirectorio'] : null;
    $nombreDirectorio = trim($nombreDirectorio);

    if (empty($nombreDirectorio)) {
        throw new InvalidArgumentException('Nombre de directorio no proporcionado');
    }

    $rutaDirectorioActual = "/UTA/FISEI/" . $nombreDirectorio;

    $nuevoNombreDirectorio = isset($_POST['nuevoNombreDirectorio']) ? $_POST['nuevoNombreDirectorio'] : null;

    if (empty($nuevoNombreDirectorio)) {
        throw new InvalidArgumentException('Nuevo nombre de directorio no proporcionado');
    }

    $rutaNuevoDirectorio = "/UTA/FISEI/" . $nuevoNombreDirectorio;

    // Cambiar el nombre del directorio en el servidor SFTP
    if ($sftp->rename($rutaDirectorioActual, $rutaNuevoDirectorio)) {
        $response = ['success' => true, 'message' => 'Directorio renombrado correctamente.'];
    } else {
        $response = ['success' => false, 'message' => 'Error al renombrar el directorio.', 'error' => $sftp->getLastSFTPError()];
    }

} catch (InvalidArgumentException $ex) {
    $response = ['success' => false, 'message' => $ex->getMessage()];
} catch (Exception $ex) {
    $response = ['success' => false, 'message' => 'Error general: ' . $ex->getMessage()];
}


echo json_encode($response);

$sftp->disconnect();
?>