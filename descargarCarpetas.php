<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once ('Servidor.php');
require_once ('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function downloadFolder($sftp, $folderPath, $localPath)
{
    $files = $sftp->nlist($folderPath);
    echo "Archivos en la carpeta remota: " . PHP_EOL;
    print_r($files);

    if (!file_exists($localPath)) {
        echo "Creando directorio local: " . $localPath . PHP_EOL;
        mkdir($localPath, 0777, true);
    }

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $remoteFilePath = $folderPath . '/' . $file;
            $localFilePath = $localPath . '/' . $file;
            if ($sftp->is_dir($remoteFilePath)) {
                echo "Descargando carpeta: " . $remoteFilePath . PHP_EOL;
                downloadFolder($sftp, $remoteFilePath, $localFilePath);
            } else {
                echo "Descargando archivo: " . $remoteFilePath . PHP_EOL;
                $sftp->get($remoteFilePath, $localFilePath);
            }
        }
    }
}

try {
    $ruta = $_POST['rutaRemota'];
    $rutaremota = '/UTA/FISEI/' . $ruta;

    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        throw new Exception('No se pudo autenticar en el servidor SFTP');
    } else {
        echo "Inicio de sesión exitoso en el servidor SFTP" . PHP_EOL;

        // Crear una carpeta temporal para almacenar los archivos descargados
        $tempDir = sys_get_temp_dir() . uniqid('sftp_download_');
        //$tempDir = '/home/acceso/temp/' . uniqid('sftp_download_');
        echo "El script se está ejecutando bajo el usuario: " . get_current_user() . PHP_EOL;
        echo $tempDir . PHP_EOL;
        echo sys_get_temp_dir() . PHP_EOL;
        mkdir($tempDir, 0777, true);
        chmod($tempDir, 0777);

        var_dump(file_exists($tempDir));

        echo "Inicio de descarga de archivos..." . PHP_EOL;
        downloadFolder($sftp, $rutaremota, $tempDir);

        // Comprimir la carpeta descargada en un archivo ZIP
        $zipFileName = basename($rutaremota) . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            echo "Comprimiendo archivos..." . PHP_EOL;
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($tempDir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                echo "Agregando archivo al ZIP: " . $file . PHP_EOL;
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempDir) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            echo "Archivo ZIP creado: " . $zipFileName . PHP_EOL;

            // Descargar el archivo ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            readfile($zipFileName);

            // Eliminar la carpeta temporal y el archivo ZIP después de la descarga
            removeDir($tempDir);
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
