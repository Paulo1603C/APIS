<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('Servidor.php');
require_once('vendor/autoload.php');
use phpseclib3\Net\SFTP;

function findEmptyFolders($sftp, $directory)
{
    $emptyFolders = array();

    // Obtener lista de archivos y carpetas en el directorio actual
    $files = $sftp->nlist($directory);

    foreach ($files as $file) {
        // Ignorar las carpetas . y ..
        if ($file != '.' && $file != '..') {
            // Construir la ruta completa del archivo o carpeta
            $path = $directory . '/' . $file;
            // Verificar si es una carpeta
            if ($sftp->is_dir($path)) {
                // Recursivamente buscar carpetas vacías
                $subfolderEmpty = findEmptyFolders($sftp, $path);
                $informacionDirectorio = $sftp->rawlist($path);
                unset($informacionDirectorio['.']);
                unset($informacionDirectorio['..']);
                foreach ($informacionDirectorio as $nombre => $detalles) {
                    $elemento = [
                        'carVacia' => $path . "/" . $nombre
                    ];
                    $tipo = ($detalles['type'] === 2 ? 'Directorio' : 'Archivo');
                    $tamano = ($detalles['type'] === 2 ? round($detalles['size']) : round($detalles['size'] / (1024 * 1024), 2));
                    if ($tipo == 'Directorio' && $tamano <= 6) {
                        $emptyFolders[] = $elemento;
                    }
                }
            }
        }
    }
    return $emptyFolders;
}

require ('conexion.php');

$idUser = $_POST['IdUser'];
$listCarreras = array();
$selectCarreras = "SELECT DISTINCT c.idCar, c.nomCar 
            FROM carreras as c
            JOIN carreras_Secretarias as cs ON cs.IdCarPer = c.IdCar
            JOIN usuarios as u ON u.IdUser = cs.IdUserPer
            where IdUser = :IdUser
            ORDER BY c.nomCar";
$selectCarreras = $connect->prepare($selectCarreras);
$selectCarreras->bindParam(':IdUser', $idUser, PDO::PARAM_INT);

try {
    $selectCarreras->execute();
    $result = $selectCarreras->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $item) {
        array_push($listCarreras, $item['nomCar']);
    }

} catch (\Throwable $th) {
    $response = array("error" => "Error" . $th);
    echo json_encode($response);
    exit;
}

// Configurar la conexión SFTP
if (sizeof($listCarreras) > 0 ) {
    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        $response = array("error" => "No se pudo autenticar en el servidor SFTP");
        echo json_encode($response);
        exit;
    }

    $emptyFolders = array();

    try {
        foreach ($listCarreras as $carrera) {
            $rootDirectory = '/UTA/FISEI/CARRERAS/' . mb_strtoupper($carrera);
            $emptyFolders = array_merge($emptyFolders, findEmptyFolders($sftp, $rootDirectory));
        }

        // Extraer los nombres de las carpetas vacías y ordenarlos
        $names = array_map(function($folder) {
            return $folder['carVacia'];
        }, $emptyFolders);

        sort($names);

        // Construir el array ordenado de objetos
        $sortedFolders = array_map(function($name) {
            return array("carVacia" => $name);
        }, $names);

        echo json_encode($sortedFolders);
    } catch (\Throwable $th) {
        $response = array("error" => "Error " . $th);
        echo json_encode($response);
    }
} else {
    $response = array("error" => "Necesitas un nombre de carrera");
    echo json_encode($response);
}
?>
