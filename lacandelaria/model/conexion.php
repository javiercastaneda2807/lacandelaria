<?php 
$db_host = 'localhost'; 
$contrasena = "leo1234";
$usuario = "root";
$nombre_bd = "bdcandelaria";

$db = new mysqli("$db_host", "$usuario", "$contrasena", "$nombre_bd");

if ($db->connect_errno) {
    echo 'Error al conectar a la base de datos: ' . $db->connect_error;
    exit();
}
?>

