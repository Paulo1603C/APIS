<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

try {
    // Limite de tamaño en bytes (50 MB)
    $maxFileSize = 50 * 1024 * 1024;

    // Validar tamaño del archivo
    if ($_FILES['file']['size'] > $maxFileSize) {
        echo json_encode(['Error' => true, 'message' => 'El archivo excede el tamaño máximo permitido de 50 MB']);
        exit;
    }

    // Comprobar si hubo algún error en la subida
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['Error' => true, 'message' => 'Error al subir el archivo: ' . $_FILES['file']['error']]);
        exit;
    }

    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        $ruta = $_POST['rutaRemota'];
        $remoteDirectory = "/UTA/FISEI/" . $ruta;
        $uploadedFileName = $_FILES['file']['name'];
        $localFilePath = $_FILES['file']['tmp_name'];
        var_dump($_FILES);

        if ($sftp->put($remoteDirectory . $uploadedFileName, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
            echo json_encode(['Error' => false, 'message' => 'Archivo subido con éxito']);
        } else {
            echo json_encode(['Error' => true, 'message' => 'Error al subir el archivo']);
        }
    }
} catch (\Throwable $th) {
    echo json_encode(['Error' => true, 'message' => 'Error: ' . $th->getMessage()]);
} finally {
    $sftp->disconnect();
}
?>
