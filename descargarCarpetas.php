<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function downloadFolder($sftp, $folderPath, $localPath) {
    $files = $sftp->nlist($folderPath);

    if (!file_exists($localPath)) {
        mkdir($localPath, 0777, true);
    }

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $remoteFilePath = $folderPath . '/' . $file;
            $localFilePath = $localPath . '/' . $file;
            if ($sftp->is_dir($remoteFilePath)) {
                downloadFolder($sftp, $remoteFilePath, $localFilePath);
            } else {
                $sftp->get($remoteFilePath, $localFilePath);
            }
        }
    }
}

try {
    $rutaremota = '/UTA/FISEI/CARRERAS';

    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        // Crear una carpeta temporal para almacenar los archivos descargados
        $tempDir = 'C:/Temp/' . uniqid('sftp_download_');
        mkdir($tempDir);

        // Descargar la carpeta y sus archivos
        downloadFolder($sftp, $rutaremota, $tempDir);

        // Comprimir la carpeta descargada en un archivo ZIP
        $zipFileName = basename($rutaremota) . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($tempDir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempDir) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        } else {
            throw new Exception('No se pudo crear el archivo ZIP');
        }

        // Descargar el archivo ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' .$zipFileName . '"');
        readfile($zipFileName);

        // Eliminar la carpeta temporal y el archivo ZIP después de la descarga
        removeDir($tempDir);
        unlink($zipFileName);
    }
} catch (\Throwable $th) {
    echo json_encode(['Error' => true, 'message' => 'Error: ' . $th->getMessage()]);
} finally {
    if (isset($sftp)) {
        $sftp->disconnect();
    }
}

// Función para eliminar una carpeta y su contenido recursivamente
function removeDir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    removeDir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}
?>
