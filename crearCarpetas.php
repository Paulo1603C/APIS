<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

$sftp = new SFTP($servidor, $puerto);

try {
    if (!$sftp->login($user, $pass)) {
        die('No se pudo autenticar en el servidor SFTP');
    } else {
        //$path = $_POST['ruta'];

        $nombreDirectorio = isset($_POST['nuevoNombreDirectorio']) ? $_POST['nuevoNombreDirectorio'] : null;
        $nombreDirectorio = trim($nombreDirectorio);
        if (empty($nombreDirectorio)) {
            throw new InvalidArgumentException('Nombre de directorio no proporcionado');
        }

        $ruta = "/UTA/FISEI/".$nombreDirectorio;
        if ($sftp->mkdir($ruta)) {
            echo json_encode("Carpeta creada");
        } else {
            echo json_encode("Carpeta no creada. Error: " . $sftp->getLastSFTPError());
        }
    }

} catch (\Throwable $th) {
    die('Mensaje de error: ' . $th->getMessage());
}

$sftp->disconnect();
?>