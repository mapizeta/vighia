<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';

$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');

switch ($task) {	

	case 'save-group':
		$grupo = getXss($_REQUEST, 'grupo');
		saveGroup($grupo);
		break;
		
	case 'save-geofence':
		$action = getXss($_REQUEST, 'action');
		$nombre = getXss($_REQUEST, 'nombre');
		$descripcion = getXss($_REQUEST, 'descripcion');
		$unidad = getXss($_REQUEST, 'unidad');
		$grupo = getXss($_REQUEST, 'grupo');
		$marcador = getXss($_REQUEST, 'marcador');
		saveGeofence($action, $nombre, $descripcion, $unidad, $grupo, $marcador);
		break;
	case 'move-geo':
		$id_grupo = getXss($_REQUEST, 'id_grupo');
		move_geo($id_grupo);
		break;
		
	case 'delete-geo':
		deleteGeo();
		break;
		
	case 'edit-group':
		$grupo = getXss($_REQUEST, 'grupo');
		editGroup($grupo);
		break;
		
	case 'delete-group':
		deleteGroup();
		break;

}

function saveGroup($grupo){
	global $db, $id;
	
	if(!$grupo):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar un nombre para el grupo';
	else:
		
		$db->query("INSERT INTO vigia.grupos (grupo, id_compania) VALUES ('$grupo', $id)");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Grupo creado correctamente';
	endif;
		
	echo json_encode($response);

}

function saveGeofence($action, $nombre, $descripcion, $unidad, $grupo, $marcador){
	global $db;
	
	$coord = explode(';', $action);
	if(!$marcador) $marcador=1;
	
	if(!$action):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe Dibujar una geocerca';
	elseif(!$unidad):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe asignar una unidad';
	elseif(!$nombre):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar un nombre para la geocerca';
	else:
		
		$sql = "INSERT INTO vigia.geofences (nombre, descripcion, fk_id_tipo, fk_id_grupos, fk_id_unidad, id_puntos_interes) 
				VALUES ('$nombre', '$descripcion', ".obtener_id_tipo($coord[0]).", $grupo, $unidad, $marcador)";
		$res = $db->query($sql);
		
		$id_geofence=ultima_geofence();
		
		//condicion si es circunferencia
		if($coord[0] == 'Circle'){
		$del_parentesis=array("(",")");
		$clean_coord = str_replace($del_parentesis, "", $coord[1]);
		guarda_coord_circle($clean_coord, $id_geofence);
		}
		else
		for($i=1;$i<count($coord)-1;$i++){
				$del_parentesis=array("(",")");
				$clean_coord = str_replace($del_parentesis, "", $coord[$i]);
				guarda_coord($clean_coord, $id_geofence);
		}
		
		$response['type'] = 'OK:';
		$response['text'] = 'Geocerca creada correctamente';
	endif;
		
	echo json_encode($response);

}

function move_geo($id_grupo){
	global $db, $id;
		
	if(!$id_grupo):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger un grupo';
	else:
		
		$db->query("UPDATE vigia.geofences SET fk_id_grupos =".$id_grupo." WHERE id_geofence =".$id);
		
		$response['type'] = 'OK:';
		$response['text'] = 'Geocerca movida correctamente';
	endif;
		
	echo json_encode($response);
		
}

function deleteGeo(){
	global $db, $id;
	
	if(!$id):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger una geocerca a eliminar';
	else:
		
		$db->query("DELETE FROM vigia.coordenada WHERE geofence_id_geofence=$id");
		$db->query("DELETE FROM vigia.geofences WHERE id_geofence=$id");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Geocerca eliminada correctamente';
	endif;
		
	echo json_encode($response);
}

function editGroup($grupo){
	global $db, $id;
	
	if(!$id):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger una geocerca a eliminar';
	elseif(!$grupo):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Ingrese un nombre para el grupo';
	else:
		
		$db->query("UPDATE vigia.grupos SET grupo='$grupo' WHERE id_grupos=$id");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Grupo actualizado correctamente';
	endif;
		
	echo json_encode($response);
}

function deleteGroup(){
	global $db, $id;
	
	if(!$id):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger un grupo a eliminar';
	else:
		
		$sql = $db->query("SELECT id_geofence FROM vigia.geofences WHERE fk_id_grupos=$id");
		while($r = pg_fetch_object($sql)):
			$sql2 = $db->query("SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$r->id_geofence);
			while($d = pg_fetch_object($sql2)):
				$db->query("DELETE FROM vigia.coordenada WHERE id_coordenada=".$d->id_coordenada);
			endwhile;
			$db->query("DELETE FROM vigia.geofences WHERE id_geofence=".$r->id_geofence);
		endwhile;
		
		$db->query("DELETE FROM vigia.grupos WHERE id_grupos=$id");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Grupo eliminado correctamente';
	endif;
		
	echo json_encode($response);
}

