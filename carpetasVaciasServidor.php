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
            //echo $path . "\n";
            // Verificar si es una carpeta
            if ($sftp->is_dir($path)) {
                // Recursivamente buscar carpetas vacías
                $subfolderEmpty = findEmptyFolders($sftp, $path);
                $informacionDirectorio = $sftp->rawlist($path);
                unset($informacionDirectorio['.']);
                unset($informacionDirectorio['..']);
                foreach ($informacionDirectorio as $nombre => $detalles) {
                    $elemento = [
                        'nombre' => $nombre,
                        'tipo' => ($detalles['type'] === 2 ? 'Directorio' : 'Archivo'),
                        'tamaño' => ($detalles['type'] === 2 ? round($detalles['size']) : round($detalles['size'] / (1024 * 1024), 2)),
                    ];
                    $tipo = $elemento['tipo'];
                    $tamano = $elemento['tamaño'];
                    if ($tipo == 'Directorio' && $tamano <= 6) {
                        array_push($emptyFolders, $path . "/" . $elemento['nombre']);
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
            FROM Carreras as c
            JOIN Carreras_Secretarias as cs ON cs.IdCarPer = c.IdCar
            JOIN Usuarios as u ON u.IdUser = cs.IdUserPer
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
    echo json_encode("Error" . $th);
}

// Configurar la conexión SFTP
if (sizeof($listCarreras) > 0 ) {
    $sftp = new SFTP($servidor, $puerto);
    if (!$sftp->login($user, $pass)) {
        die('No se pudo autenticar en el servidor SFTP');
    }

    try {
        $i=0;
        while( $i<sizeof($listCarreras)){
            $rootDirectory = '/UTA/FISEI/CARRERAS/'.mb_strtoupper($listCarreras[$i]);
            $emptyFolders = findEmptyFolders($sftp, $rootDirectory);
            $i++;
        }
        echo json_encode($emptyFolders, JSON_UNESCAPED_UNICODE);
    } catch (\Throwable $th) {
        echo json_encode("Error " . $th);
    }
} else {
    echo json_encode("Necesitass un nombre de carrera");
}


?>