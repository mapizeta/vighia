<?
include "dbpgsql.php";
date_default_timezone_set("Chile/Continental");
if($_POST)
{
    $keys_post = array_keys($_POST);
    foreach ($keys_post as $key_post)
     {
      $$key_post = $_POST[$key_post];
      error_log("variable $key_post viene desde $ _POST");
     }
} 

if($_GET)
{
    $keys_get = array_keys($_GET);
    foreach ($keys_get as $key_get)
     {
        $$key_get = $_GET[$key_get];
        error_log("variable $key_get viene desde $ _GET");
     }
} 

//Variables de conexion
$host="vighiaprime.com";
$nombre_bd="vpro";
$usuario="kusko";
$clave="alpha242526";


$db = new Database();
$db->setBD($host, $nombre_bd, $usuario, $clave);

?>
