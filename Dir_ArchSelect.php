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
    }

    $ruta = $_POST['rutaServidor'];

    // Obtener lista de archivos y directorios en el directorio remoto
    $ruta = '/UTA/FISEI/' . $ruta;

    // Obtener información completa de archivos y directorios en el directorio remoto
    $informacionDirectorio = $sftp->rawlist($ruta);

    // Filtrar los directorios especiales "." y ".."
    unset($informacionDirectorio['.']);
    unset($informacionDirectorio['..']);

    // Inicializar un array para almacenar la información
    $informacionArray = [];

    // Iterar sobre la información del directorio
    foreach ($informacionDirectorio as $nombre => $detalles) {
        // Construir un array asociativo con la información
        $elemento = [
            'nombre' => $nombre,
            'tipo' => ($detalles['type'] === 2 ? 'Directorio' : 'Archivo'),
            'tamaño' => ($detalles['type'] === 2 ? round($detalles['size'] / (1024 * 1024), 7) : round($detalles['size'] / (1024 * 1024), 2)),
        ];

        // Obtener la fecha de creación
        $fechaCreacion = isset($detalles['mtime']) ? date('d-m-Y', $detalles['mtime']) : 'No disponible';
        $elemento['fecha_creacion'] = $fechaCreacion;

        // Obtener información adicional para directorios
        if ($detalles['type'] === 2) {
            $auxRuta = $ruta . '/' . $nombre;
            $informacionDirectorio2 = $sftp->rawlist($auxRuta);
            // Filtrar los directorios especiales "." y ".."
            unset($informacionDirectorio2['.']);
            unset($informacionDirectorio2['..']);

            $cantidadArchivos = count($informacionDirectorio2);
            $elemento['cantidad'] = $cantidadArchivos;
        }

        // Verificar si 'mode' está definido antes de intentar acceder a 'permissions'
        if (isset($detalles['mode'])) {
            $elemento['permisos'] = $detalles['mode'];
        } else {
            $elemento['permisos'] = 'No disponibles';
        }

        // Agregar el array del elemento al array principal
        $informacionArray[] = $elemento;
    }

    // Ordenar el array por el campo 'nombre'
    usort($informacionArray, function ($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });

    // Convertir el array a formato JSON
    $jsonResponse = json_encode(['elementos' => $informacionArray]);

    echo $jsonResponse;

    $sftp->disconnect();
} catch (Exception $e) {
    // Captura de excepciones
    $errorMessage = json_encode(['error ' => $e->getMessage()]);
    echo $errorMessage;
}
?>
