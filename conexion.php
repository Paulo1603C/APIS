<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
//define('DB_PASS','Servicios.Fisei@2024');
define('DB_NAME','uta');
/*define('DB_HOST','sql305.infinityfree.com');
define('DB_USER','if0_36161740');
define('DB_PASS','DNI33HUWzMt');
define('DB_NAME','if0_36161740_uta');*/

try {
    $connect = new PDO( 'mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS,
                array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" ) );
    //echo "conexion... ";
} catch (\Throwable $th) {
    exit("Error: " . $th->getMessage() );
}

?>