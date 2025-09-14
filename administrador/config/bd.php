<?php
$host="localhost";
$bd="chiapet";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    if($conexion){echo "Conectado a la base de datos";}
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>