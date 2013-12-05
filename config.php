<?php
session_start();
if (!$_SESSION['user'])
header("Location: ../index.php");
/****** Archivo de configuración *****/

// Configuración Básica
$folder = '';
$admin_folder = '/admin';
if(!defined('SITE_NAME')) define('SITE_NAME', 'Control Vighia ');


// Rutas
if(!defined('SITE_PATH')) define('SITE_PATH','http://'.$_SERVER['SERVER_NAME'].$folder);
if(!defined('ABS_PATH')) define('ABS_PATH', $_SERVER['DOCUMENT_ROOT'].$folder);

// Fecha
//setlocale(LC_ALL,"es_ES@euro","es_ES","esp");


?>