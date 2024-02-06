<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

try {
    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {

        //$remoteDirectory = "/UTA/FISEI/CARRERAS/INGENIERÍA INDUSTRIAL/ANITA LÓPEZ/";
        $ruta=$_POST['rutaRemota'];
        $remoteDirectory = "/UTA/FISEI/".$ruta;
        $uploadedFileName = $_FILES['file']['name'];
        $localFilePath = $_FILES['file']['tmp_name'];
        var_dump($_FILES);

        if ($sftp->put($remoteDirectory . $uploadedFileName, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
            echo 'Archivo subido con éxito';
        } else {
            echo 'Error al subir el archivo';
        }
    }
} catch (\Throwable $th) {
    echo json_encode(['Error' => false, 'message' => 'Error: ' . $th->getMessage()]);
} finally {
    $sftp->disconnect();
}
?>