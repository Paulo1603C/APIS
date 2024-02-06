<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

try {
    // Rutas remotas y locales
    //$rutaremota = '/UTA/FISEI/CARRERAS/INGENIERÍA INDUSTRIAL/ANITA LÓPEZ/Framework.pdf';
    $ruta=$_POST['rutaRemota'];
    $rutaremota = '/UTA/FISEI/'.$ruta;

    // Conexión SFTP
    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        // Obtener el contenido del archivo
        $contenido = $sftp->get($rutaremota);

        // Encabezados para forzar la descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($rutaremota) . '"');

        // Imprimir el contenido del archivo
        echo $contenido;
    }
} catch (\Throwable $th) {
    echo json_encode(['Error' => true, 'message' => 'Error: ' . $th->getMessage()]);
} finally {
    if (isset($sftp)) {
        $sftp->disconnect();
    }
}
?>