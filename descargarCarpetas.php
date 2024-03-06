<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function safeUrl($url) {
    // Verificar si la URL ya tiene el protocolo HTTPS
    if (strpos($url, 'https://') === 0) {
        return $url; // La URL ya es segura, retornarla sin cambios
    } elseif (strpos($url, 'http://') === 0) {
        // La URL no tiene HTTPS, reemplazar HTTP por HTTPS
        return str_replace('http://', 'https://', $url);
    } else {
        return $url; // No es una URL HTTP, retornarla sin cambios
    }
}

function downloadFolder($sftp, $folderPath, $localPath)
{
    $files = $sftp->nlist($folderPath);

    if (!file_exists($localPath)) {
        mkdir($localPath, 0777, true);
    }

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $remoteFilePath = $folderPath . '/' . $file;
            $localFilePath = $localPath . '/' . $file;
            if ($sftp->is_dir($remoteFilePath)) {
                // Descargar carpetas recursivamente, incluso si están vacías
                downloadFolder($sftp, $remoteFilePath, $localFilePath);
            } else {
                // Si es un archivo, descargarlo
                $sftp->get($remoteFilePath, $localFilePath);
            }
        }
    }

    // Si no hay archivos ni carpetas en la carpeta remota, creamos una carpeta vacía en la carpeta local
    if (empty($files)) {
        mkdir($localPath, 0777, true);
    }
}



try {
    $ruta = $_POST['rutaRemota'];
    $rutaremota = '/UTA/FISEI/' . $ruta;

    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        // Crear una carpeta temporal para almacenar los archivos descargados
        $tempDir = sys_get_temp_dir() . '/' . uniqid('sftp_download_');
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
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempDir) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            throw new Exception('No se pudo crear el archivo ZIP');
        }

        // Descargar el archivo ZIP
        $zipUrl = safeUrl($zipFileName);
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        readfile($zipUrl); // Utilizar safeUrl para generar la URL segura



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
function removeDir($dir)
{
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