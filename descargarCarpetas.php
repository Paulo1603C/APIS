<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function downloadDirectory($sftp, $remoteDir, $localBaseDir)
{
    $sftp->chdir($remoteDir);
    
    // Obtener el nombre del directorio remoto
    $remoteDirName = basename($remoteDir);
    
    // Crear la carpeta con el nombre del servidor 
    $localDir = $localBaseDir . '/' . $remoteDirName;
    if (!is_dir($localDir)) {
        mkdir($localDir, 0777, true);
    }

    $files = $sftp->nlist();
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $remotePath = $remoteDir . '/' . $file;
            $localPath = $localDir . '/' . $file;

            if ($sftp->is_dir($remotePath)) {
                // Si es un directorio, descarga su contenido recursivamente
                downloadDirectory($sftp, $remotePath, $localDir);
            } else {
                // Si es un archivo, descárgalo
                if (!$sftp->get($remotePath, $localPath)) {
                    die('Error -> no se pudo descargar el archivo ' . $remotePath);
                }
            }
        }
    }

    static $successMessagePrinted = false;
    if (!$successMessagePrinted) {
        echo json_encode("Carpeta descargada");
        $successMessagePrinted = true;
    }
}

$sftp = new SFTP($servidor, $puerto);
if (!$sftp->login($user, $pass)) {
    die('No se pudo autenticar en el servidor SFTP');
}

// Especifica la ruta de la carpeta que deseas crear
$carpetaNueva = 'C:/DESCARGAS';

// Verifica si la carpeta ya existe
if (!is_dir($carpetaNueva)) {
    // Intenta crear la carpeta
    if (mkdir($carpetaNueva, 0777, true)) {
        echo 'Carpeta creada correctamente: ' . $carpetaNueva;
    } else {
        echo 'Error al crear la carpeta: ' . $carpetaNueva;
    }
} else {
    echo 'La carpeta ya existe: ' . $carpetaNueva;
}

$ruta = $_POST['rutaRemota'];
$remoteDir = '/UTA/FISEI/'.$ruta;
$remoteDir = rtrim($remoteDir, '/');
$localDir = $carpetaNueva;

// Descargar el directorio
downloadDirectory($sftp, $remoteDir, $localDir);
?>
