<?php
session_id($_REQUEST['sid']);
session_start();
require_once("../../../config.php"); // llamo al archivo de configuración base
require_once ABS_PATH.'/include/security/XSS.php'; // Llama al script de filtro de variables
require_once ABS_PATH.'/include/conect.php'; // Llamada a la conexión de Bases de Datos
require_once ABS_PATH.'/include/scripts/php/phpThumb/ThumbLib.inc.php'; // Llamo al redimensionador de imagenes

$id = getXss($_REQUEST, 'id');
$task = getXss($_REQUEST, 'task');
$sid = getXss($_REQUEST, 'sid');
$name = $_FILES['Filedata']['name']; 
$tmp = $_FILES['Filedata']['tmp_name'];
$type = $_FILES['Filedata']['type'];

$sql = $db->query("SELECT sid FROM vigia.usuario WHERE sid='".$sid."'");
$d = pg_fetch_object($sql);
pg_free_result($sql);

		
if($d->sid)
{
	if($d->sid == $sid)
	{
		if(!empty($_FILES)){
			switch($task){
				case 'image':
					imageUploader( $name, $tmp, $type);
					break;
			}
		}
	}else{echo 'logout';}
}else{echo 'logout';}


function imageUploader( $name, $tmp, $type){
	global $db, $id;
		
	$fileParts = pathinfo($name);
	$ext = strtolower($fileParts['extension']);
	
	if($type == 'image/jpeg' || $type == 'application/octet-stream'){
		
		$fileName = uniqid("img_").".".$ext;	
		$tmpUpload = "../../../TMP/".$fileName;
		$dest = ABS_PATH."/pictures/images";
		
		if(move_uploaded_file($tmp, $tmpUpload)){
			try{ 
				$db->query("INSERT INTO vigia.foto (imagen, id_unidad) VALUES ('insertada2', $id)");
				$thumb = PhpThumbFactory::create($tmpUpload);
				unlink($tmpUpload);
				$db->query("INSERT INTO vigia.foto (imagen, id_unidad) VALUES ('insertada2', $id)");
				$thumb->resize(320, 240); $thumb->save($dest.'_320/'.$fileName);
				$thumb->resize(800, 600); $thumb->save($dest.'_800/'.$fileName);
				
				$sql = $db->query("SELECT imagen FROM vigia.foto WHERE id_unidad=".$id);
				$r = pg_fetch_object($sql);
				
				if($r->imagen):
					deleteImages($r->imagen);
					$db->query("UPDATE vigia.foto SET imagen='$fileName' WHERE id_unidad=".$id);
				else:
					$db->query("INSERT INTO vigia.foto (imagen, id_unidad) VALUES ('$fileName', $id)");
				endif;
				
				echo $fileName;
			}catch (Exception $e){
				 echo "Error_Rezise";
				 $db->query("INSERT INTO vigia.foto (imagen, id_unidad) VALUES ('$e', $id)");
			}
		}else{
			unlink($tmpUpload);
		}
		
			
	}else echo "Formato de archivo no soportado";
}

function deleteImages($imagen){
	$rutas = array('images_320', 'images_800');
	foreach($rutas as $ruta){
		$img = ABS_PATH.'/pictures/'.$ruta.'/'.$imagen;
		if(file_exists($img)) if(is_file($img)) unlink($img);
	}
}

