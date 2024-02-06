<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

// Para almacenar la ruta de los archivos cargados
$files_arr = array();

if (isset($_FILES['files']['name'])) {

    var_dump($_FILES);

    // Contar archivos totales
    $countfiles = isset($_FILES['files']['name'])  ? count($_FILES['files']['name']) : 0;
    echo gettype ($_FILES);
    echo 'Cantidad '.$countfiles;
    // Configuración de conexión SFTP
    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    }

    // Subir directorio en el servidor SFTP
    $upload_location = "/UTA/FISEI/CARRERAS/INGENIERÍA INDUSTRIAL/ANITA LÓPEZ";

    // Ciclo para todos los archivos
    for ($index = 0; $index < $countfiles; $index++) {

        if (isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != '') {
            // Nombre del archivo
            $filename = $_FILES['files']['name'][$index];

            // Obtener la extensión del archivo
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Validar extensiones permitidas
            $valid_ext = array("png", "jpeg", "jpg", "pdf", "txt", "doc", "docx");

            // Revisar extensión
            if (in_array($ext, $valid_ext)) {

                // Ruta de archivo en el servidor SFTP
                $newfilename = time() . "_" . $filename;
                $remote_path = $upload_location . '/' . $newfilename;

                // Subir archivos al servidor SFTP
                if ($sftp->put($remote_path, $_FILES['files']['tmp_name'][$index], SFTP::SOURCE_LOCAL_FILE)) {
                    $files_arr[] = $newfilename;
                }
            }
        }
    }

    // No es necesario cerrar la conexión SFTP

}

echo json_encode($files_arr);
die;
?>
