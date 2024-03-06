<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function downloadFolder($sftp, $folderPath, $localPath, $zip) {
    $files = $sftp->nlist($folderPath);

    if (!file_exists($localPath)) {
        mkdir($localPath, 0777, true);
    }

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            if ($sftp->is_dir($folderPath . '/' . $file)) {
                downloadFolder($sftp, $folderPath . '/' . $file, $localPath . '/' . $file, $zip);
            } else {
                $remoteFilePath = $folderPath . '/' . $file;
                $localFilePath = $localPath . '/' . $file;
                $sftp->get($remoteFilePath, $localFilePath);
                $zip->addFile($localFilePath, substr($localFilePath, strlen($localPath) + 1));
            }
        }
    }
}

try {
    $ruta=$_POST['rutaRemota'];
    $rutaremota = '/UTA/FISEI/'.$ruta;

    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        // Crear archivo ZIP
        $zip = new ZipArchive();
        $zipFileName = basename($rutaremota) . '.zip';
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            downloadFolder($sftp, $rutaremota, basename($rutaremota), $zip);
            $zip->close();

            // Descargar el archivo ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            readfile($zipFileName);

            // Eliminar el archivo ZIP después de la descarga
            unlink($zipFileName);
        } else {
            throw new Exception('No se pudo crear el archivo ZIP');
        }
    }
} catch (\Throwable $th) {
    echo json_encode(['Error' => true, 'message' => 'Error: ' . $th->getMessage()]);
} finally {
    if (isset($sftp)) {
        $sftp->disconnect();
    }
}
?>
