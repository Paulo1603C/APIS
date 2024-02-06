<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

$sftp = new SFTP($servidor, $puerto);
if (!$sftp->login($user, $pass)) {
    die('No se pudo autenticar en el servidor SFTP');
}

$ruta = isset($_POST['rutaServidor']) != null ? $_POST['rutaServidor'] : null;

if ($ruta != null) {
    $rutaCompleta="UTA/FISEI/".$ruta;
    if ($sftp->delete($rutaCompleta)) {
        $response = ['success' => true, 'message' => 'Directorio eliminado correctamente.'];
    } else {
        $response = ['success' => false, 'message' => 'Error al eliminar el directorio.', 'error' => $sftp->getLastSFTPError()];
    }
} else {
    $response = ['success' => false, 'message' => 'Ruta del directorio no proporcionada.'];
}

echo json_encode($response);

$sftp->disconnect();
?>
