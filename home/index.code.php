<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';

$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');

switch ($task) {	

	default:
		index();
		break;
		
	case 'save-fleet':
		$flota = getXss($_REQUEST, 'flota');
		$contacto = getXss($_REQUEST, 'contacto');
		$fono1 = getXss($_REQUEST, 'fono1');
		$fono2 = getXss($_REQUEST, 'fono2');
		$direccion = getXss($_REQUEST, 'direccion');
		$descripcion = getXss($_REQUEST, 'descripcion');
		saveFleet($flota, $contacto, $fono1, $fono2, $direccion, $descripcion);
		break;
		
	case 'update-fleet':
		$flota = getXss($_REQUEST, 'flota');
		$contacto = getXss($_REQUEST, 'contacto');
		$fono1 = getXss($_REQUEST, 'fono1');
		$fono2 = getXss($_REQUEST, 'fono2');
		$direccion = getXss($_REQUEST, 'direccion');
		$descripcion = getXss($_REQUEST, 'descripcion');
		//Subflota **
		$padre_o = getXss($_REQUEST, 'padre_o');
		$padre = getXss($_REQUEST, 'padre');		
		
		updateFleet($flota, $contacto, $fono1, $fono2, $direccion, $descripcion, $padre_o, $padre);
		break;
				
	case 'save-unit':
		$imei = getXss($_REQUEST, 'imei');
		$nombre = getXss($_REQUEST, 'nombre');
		$codigo = getXss($_REQUEST, 'codigo');
		$descripcion = getXss($_REQUEST, 'descripcion');
		$patente = getXss($_REQUEST, 'patente');
		$marca = getXss($_REQUEST, 'marca');
		$ano = getXss($_REQUEST, 'ano');
		$conductor = getXss($_REQUEST, 'conductor');
		$fecha_mantencion = getXss($_REQUEST, 'fecha_mantencion');
		$sensor1 = getXss($_REQUEST, 'sensor1');
		$sensor2 = getXss($_REQUEST, 'sensor2');
		$sensor3 = getXss($_REQUEST, 'sensor3');
		$sensor4 = getXss($_REQUEST, 'sensor4');
		$tipo_gps = getXss($_REQUEST, 'tipo_gps');
		$numero_sim = getXss($_REQUEST, 'numero_sim');
		$numero_fono = getXss($_REQUEST, 'numero_fono');
		$id_marcador = getXss($_REQUEST, 'id_marcador');
		saveUnit($imei, $nombre, $codigo, $descripcion, $patente, $marca, $ano, $conductor, $fecha_mantencion, $sensor1, $sensor2, $sensor3, $sensor4, $tipo_gps, $numero_fono, $numero_sim, $id_marcador);
		break;
		
	case 'save-subfleet':
		$subfleet = getXss($_REQUEST, 'subfleet');
		$contacto = getXss($_REQUEST, 'contacto');
		$descripcion = getXss($_REQUEST, 'descripcion');
		saveSubfleet($subfleet, $contacto, $descripcion);
		break;
		
	case 'save-company':
		$compania = getXss($_REQUEST, 'compania');
		$rut = getXss($_REQUEST, 'rut');
		$direccion = getXss($_REQUEST, 'direccion');
		$contacto = getXss($_REQUEST, 'contacto');
		$mail = getXss($_REQUEST, 'mail');
		$fecha_pago = getXss($_REQUEST, 'fecha_pago');
		saveCompany($compania, $rut, $direccion, $contacto, $mail, $fecha_pago);
		break;
		
	case 'update-company':
		$compania = getXss($_REQUEST, 'compania');
		$rut = getXss($_REQUEST, 'rut');
		$direccion = getXss($_REQUEST, 'direccion');
		$contacto = getXss($_REQUEST, 'contacto');
		$mail = getXss($_REQUEST, 'mail');
		$fecha_pago = getXss($_REQUEST, 'fecha_pago');
		updateCompany($compania, $rut, $direccion, $contacto, $mail, $fecha_pago);
		break;
		
	case 'update-unit':
		$idle = getXss($_REQUEST, 'idle');
		$distancia = getXss($_REQUEST, 'distancia');
		$externo = getXss($_REQUEST, 'externo');
		$motor = getXss($_REQUEST, 'motor');
		$tablero = getXss($_REQUEST, 'tablero');
		//PESTAÑA UNIDAD
		$nombre = getXss($_REQUEST, 'nombre');
		$descripcion = getXss($_REQUEST, 'descripcion');
		$observaciones = getXss($_REQUEST, 'observaciones');
		$id_marcador = getXss($_REQUEST, 'id_marcador');
		$unidad_est = getXss($_REQUEST, 'unidad_est');
		$unidad_diag = getXss($_REQUEST, 'unidad_diag');
		$id_usuario = getXss($_REQUEST, 'id_usuario');
		//PESTAÑA HARDWARE
		$numero_fono = getXss($_REQUEST, 'numero_fono');
		$numero_sim = getXss($_REQUEST, 'numero_sim');
		$modem_imei = getXss($_REQUEST, 'modem_imei');
		$plate = getXss($_REQUEST, 'plate');
		$marca = getXss($_REQUEST, 'marca');
		$tipo_gps = getXss($_REQUEST, 'tipo_gps');
		$version_unidad = getXss($_REQUEST, 'version_unidad');
		//PESTAÑA CONFIGURACION
		//configuracion mapa
		$m_tiempo = getXss($_REQUEST, 'm_tiempo');
		$m_direccion = getXss($_REQUEST, 'm_direccion');
		$m_estado = getXss($_REQUEST, 'm_estado');
		$m_velocidad = getXss($_REQUEST, 'm_velocidad');
		$m_grado = getXss($_REQUEST, 'm_grado');
		$m_distancia = getXss($_REQUEST, 'm_distancia');
		$m_horas_motor = getXss($_REQUEST, 'm_horas_motor');
		$m_volt_externo = getXss($_REQUEST, 'm_volt_externo');
		$m_volt_respaldo = getXss($_REQUEST, 'm_volt_respaldo');
		$m_alarma = getXss($_REQUEST, 'm_alarma');
		$m_tipo_viaje = getXss($_REQUEST, 'm_tipo_viaje');
		$m_tiempo_gps = getXss($_REQUEST, 'm_tiempo_gps');
		$m_satelites = getXss($_REQUEST, 'm_satelites');
		$m_hdop = getXss($_REQUEST, 'm_hdop');
		$m_latlng = getXss($_REQUEST, 'm_latlng');
		$m_gsm = getXss($_REQUEST, 'm_gsm');
		$m_obs_unidad = getXss($_REQUEST, 'm_obs_unidad');
		$m_des_unidad = getXss($_REQUEST, 'm_des_unidad');
		$m_num_plato = getXss($_REQUEST, 'm_num_plato');
		//configuracion panel
		$p_tiempo = getXss($_REQUEST, 'p_tiempo');
		$p_direccion = getXss($_REQUEST, 'p_direccion');
		$p_estado = getXss($_REQUEST, 'p_estado');
		$p_velocidad = getXss($_REQUEST, 'p_velocidad');
		$p_grado = getXss($_REQUEST, 'p_grado');
		$p_distancia = getXss($_REQUEST, 'p_distancia');
		$p_horas_motor = getXss($_REQUEST, 'p_horas_motor');
		$p_volt_externo = getXss($_REQUEST, 'p_volt_externo');
		$p_volt_respaldo = getXss($_REQUEST, 'p_volt_respaldo');
		$p_alarma = getXss($_REQUEST, 'p_alarma');
		$p_tipo_viaje = getXss($_REQUEST, 'p_tipo_viaje');
		$p_tiempo_gps = getXss($_REQUEST, 'p_tiempo_gps');
		$p_satelites = getXss($_REQUEST, 'p_satelites');
		$p_hdop = getXss($_REQUEST, 'p_hdop');
		$p_latlng = getXss($_REQUEST, 'p_latlng');
		$p_gsm = getXss($_REQUEST, 'p_gsm');
		$p_obs_unidad = getXss($_REQUEST, 'p_obs_unidad');
		$p_des_unidad = getXss($_REQUEST, 'p_des_unidad');
		$p_num_plato = getXss($_REQUEST, 'p_num_plato');
		//PSTAÑA NOTIFICACION
		$email_notificaciones = getXss($_REQUEST, 'email_notificaciones');
		//PESTAÑA MANTENCION
		$licencia = getXss($_REQUEST, 'licencia');
		$mantencion1 = getXss($_REQUEST, 'mantencion1');
		$mantencion2 = getXss($_REQUEST, 'mantencion2');
		$mantencion3 = getXss($_REQUEST, 'mantencion3');
		$man_distancia = getXss($_REQUEST, 'man_distancia');
		$man_motor = getXss($_REQUEST, 'man_motor');
		$exp_garantia = getXss($_REQUEST, 'exp_garantia');
		$chequeo = getXss($_REQUEST, 'chequeo');
		
		updateUnit($nombre, $descripcion, $observaciones, $id_marcador, $unidad_est, $unidad_diag, $id_usuario, $numero_fono, $numero_sim, $modem_imei, $plate, $marca, $tipo_gps, $version_unidad, $m_tiempo, $m_direccion, $m_estado, $m_velocidad, $m_grado, $m_distancia, $m_horas_motor, $m_volt_externo, $m_volt_respaldo, $m_alarma, $m_tipo_viaje, $m_tiempo_gps, $m_satelites, $m_hdop, $m_latlng, $m_gsm , $m_obs_unidad, $m_des_unidad, $m_num_plato, $p_tiempo, $p_direccion, $p_estado, $p_velocidad, $p_grado, $p_distancia, $p_horas_motor, $p_volt_externo, $p_volt_respaldo, $p_alarma, $p_tipo_viaje, $p_tiempo_gps, $p_satelites, $p_hdop, $p_latlng, $p_gsm, $p_obs_unidad, $p_des_unidad, $p_num_plato, $email_notificaciones, $licencia, $mantencion1, $mantencion2, $mantencion3, $man_distancia, $man_motor, $exp_garantia, $chequeo, $idle, $distancia, $externo, $motor, $tablero);
		break;

	case 'move-unit':
		$flota = getXss($_REQUEST, 'flota');
		$subflota = getXss($_REQUEST, 'subflota');
		moveUnit($flota, $subflota);
		break;
		
	case 'delete-company':
		deleteCompany();
		break;
		
	case 'delete-fleet':
		deleteFleet();
		break;
		
	case 'delete-subfleet':
		deleteSubfleet();
		break;
		
	case 'delete-unit':
		deleteUnit();
		break;
		
	case 'single-marker':
		singleMarker();
		break;
		
	case 'get-image':
		getImage();
		break;
		
	case 'erase-image':
		eraseImage();
		break;

	case 'actualizar-unit':
		actualizarUnit();
		break;
		
}

function saveFleet($flota, $contacto, $fono1, $fono2, $direccion, $descripcion){
	global $db, $id;
	
	$sql = $db->query("SELECT nombre_flota FROM vigia.flota WHERE compania_id_compania=".$id." AND nombre_flota='".$flota."' AND borrado='FALSE'");
	$r = pg_fetch_object($sql);
	
	if(!$flota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar un nombre para la flota';
	elseif($r->nombre_flota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Esta flota ya existe para esta compañia';
	else:
		
		$sql="INSERT INTO vigia.flota(nombre_flota, descripcion, contacto, fono1, fono2, direccion, compania_id_compania) 
			VALUES ('$flota', '$descripcion', '$contacto', '$fono1', '$fono2', '$direccion', $id)";
		$db->query($sql);
		
		$response['type'] = 'OK:';
		$response['text'] = 'Flota creada correctamente';
	endif;
		
	echo json_encode($response);
	
	
}

function saveUnit($imei, $nombre, $codigo, $descripcion, $patente, $marca, $ano, $conductor, $fecha_mantencion, $sensor1, $sensor2, $sensor3, $sensor4, $tipo_gps, $numero_fono, $numero_sim, $id_marcador){
	global $db, $id;
	
	if($imei):
	$sql="SELECT * FROM vigia.unidad WHERE imei=$imei";
	$res = $db->query($sql);
	$r = pg_fetch_object($res);
	endif;

	if(!$imei || !$codigo || !$patente || !$conductor || !$tipo_gps || !$numero_sim || !$numero_fono):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar los campos requeridos';
	elseif($r->imei):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Este imei ya pertenece a otra unidad';
	else:
		
		$fecha = date('Y-m-d');
				
		$sql="INSERT INTO vigia.unidad (nombre, imei, code, descripcion, patente, marca, ano, conductor, fecha_mantencion, fecha_reg, sensor1, sensor2, sensor3, sensor4, tipo_gps, numero_sim, numero_fono, id_marcadores, id_flota) 
			VALUES ('$nombre', $imei, '$code', '$descripcion', '$patente', '$marca', '$ano', '$conductor', '$fecha_mantencion', '$fecha' ,'$sensor1', '$sensor2', '$sensor3', '$sensor4', '$tipo_gps', '$numero_sim', '$numero_fono', $id_marcador, $id)";
		$db->query($sql);
				
		$sql2 = $db->query("SELECT MAX(id_unidad) AS id_unidad FROM vigia.unidad");
		$r = pg_fetch_object($sql2);
		
		$db->query("INSERT INTO socket.status_unit (imei, status, sensor, motor) VALUES ($imei, 'OFFLINE', 'FALSE', 'FALSE')");
		
		$db->query("INSERT INTO vigia.conf_mapa (id_unidad) VALUES(".$r->id_unidad.")");
		$db->query("INSERT INTO vigia.conf_panel (id_unidad) VALUES(".$r->id_unidad.")");
		$db->query("INSERT INTO vigia.new_latlng (lng, lat, id_unidad, id_marcadores) VALUES (-83547364, -39673370, ".$r->id_unidad.", $id_marcador)");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Unidad creada con exito';
	endif;
		
	echo json_encode($response);
			
}

function saveSubfleet($subfleet, $contacto, $descripcion){
	global $db, $id;
	
	$sql = $db->query("SELECT nombre_flota FROM vigia.flota WHERE nombre_flota='".$subfleet."' AND padre=".$id."");
	$r = pg_fetch_object($sql);
	
	if(!$subfleet):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar un nombre para la subflota';
	elseif($r->nombre_flota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Este nombre ya se encuantra en uso dentro de esta flota';
	else:
		
		$db->query("INSERT INTO vigia.flota (nombre_flota, contacto, descripcion, padre) VALUES ('$subfleet', '$contacto', '$descripcion', $id)");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Subflota creada correctamente';
		
	endif;
	
	echo json_encode($response);
	
}

function updateFleet($flota, $contacto, $fono1, $fono2, $direccion, $descripcion, $padre_o, $padre){
	global $db, $id;
		
	if(!$flota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar nombre de la flota';
	else:
	
		if ($padre == 'Seleccione Flota'): else:	
			if($padre != $padre_o):
				$sql2 = "UPDATE vigia.flota SET padre=$padre WHERE id_flota=$id";
				$db->query($sql2);
			endif;
		endif;
			$sql = "UPDATE vigia.flota SET nombre_flota='$flota', descripcion='$descripcion', contacto='$contacto', direccion='$direccion', fono1='$fono1', fono2='$fono2' WHERE id_flota=$id";
			$db->query($sql);
			
			$response['type'] = 'OK:';
			$response['text'] = 'Flota actualizada correctamente';
	endif;
	
	echo json_encode($response);
	
}

function saveCompany($compania, $rut, $direccion, $contacto, $mail, $fecha_pago){
	global $db;
	
	$sql = $db->query("SELECT compania FROM vigia.compania WHERE compania='$compania' AND borrado='false'");
	$r = pg_fetch_object($sql);
	$sql2 = $db->query("SELECT rut FROM vigia.compania WHERE rut='$rut'");
	$d = pg_fetch_object($sql2);
	
	if(!$compania || !$fecha_pago || !$rut || !$direccion):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar campos obligatorios';
	elseif($r->compania):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El nombre de la compañia ya existe';
	elseif($d->rut):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El Rut ya esta asignado a otra compañia';
	else:
		$sql="INSERT INTO vigia.compania (compania, rut, direccion, contacto, mail, fecha_pago) 
			VALUES ('$compania', '$rut', '$direccion', '$contacto', '$mail', '$fecha_pago')";
		$db->query($sql);
		
		$response['type'] = 'OK:';
		$response['text'] = 'Compañia creada con exito';
	endif;
		
	echo json_encode($response);

}

function updateCompany($compania, $rut, $direccion, $contacto, $mail, $fecha_pago){
	global $db, $id;
	
	$sql = $db->query("SELECT compania, id_compania FROM vigia.compania WHERE compania='$compania'");
	$r = pg_fetch_object($sql);
	$sql2 = $db->query("SELECT rut, id_compania FROM vigia.compania WHERE rut='$rut'");
	$d = pg_fetch_object($sql2);
	
	if(!$compania):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar el nombre de la compañia';
	elseif($r->compania && $r->id_compania != $id):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El nombre de la compañia ya existe';
	elseif($d->rut && $d->id_compania != $id):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El Rut ya esta asignado a otra compañia';
	else:
		$sql="UPDATE vigia.compania SET compania='$compania', rut='$rut', direccion='$direccion', contacto='$contacto', mail='$mail', fecha_pago='$fecha_pago' WHERE id_compania=$id";
		$db->query($sql);
		
		$response['type'] = 'OK:';
		$response['text'] = 'Compañia atualizada correctamente';
	endif;
		
	echo json_encode($response);
	
}

function updateUnit($nombre, $descripcion, $observaciones, $id_marcador, $unidad_est, $unidad_diag, $id_usuario, $numero_fono, $numero_sim, $modem_imei, $plate, $marca, $tipo_gps, $version_unidad, $m_tiempo, $m_direccion, $m_estado, $m_velocidad, $m_grado, $m_distancia, $m_horas_motor, $m_volt_externo, $m_volt_respaldo, $m_alarma, $m_tipo_viaje, $m_tiempo_gps, $m_satelites, $m_hdop, $m_latlng, $m_gsm , $m_obs_unidad, $m_des_unidad, $m_num_plato, $p_tiempo, $p_direccion, $p_estado, $p_velocidad, $p_grado, $p_distancia, $p_horas_motor, $p_volt_externo, $p_volt_respaldo, $p_alarma, $p_tipo_viaje, $p_tiempo_gps, $p_satelites, $p_hdop, $p_latlng, $p_gsm, $p_obs_unidad, $p_des_unidad, $p_num_plato, $email_notificaciones, $licencia, $mantencion1, $mantencion2, $mantencion3, $man_distancia, $man_motor, $exp_garantia, $chequeo, $idle, $distancia, $externo, $motor, $tablero){
	global $db, $id;
	
	if(!$nombre):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar los campos requeridos';
	else:
		
		$fecha = date('Y-m-d');
		
		$unidad_est = ($unidad_est) ? 1 : 0;
		$unidad_diag =($unidad_diag) ? 1 : 0;
		
		$db->query("UPDATE vigia.unidad SET nombre='$nombre', descripcion='$descripcion', observaciones='$observaciones', id_marcadores=$id_marcador, unidad_est='$unidad_est', unidad_diag='$unidad_diag', actualizacion='$fecha', id_usuario=$id_usuario, numero_fono='$numero_fono', numero_sim='$numero_sim', modem_imei='$modem_imei', plate='$plate', id_marca=$marca, tipo_gps='$tipo_gps', version_unidad='$version_unidad', email_notificaciones='$email_notificaciones', licencia='$licencia', mantencion1='$mantencion1', mantencion2='$mantencion2', mantencion3='$mantencion3', man_distancia='$man_distancia', man_motor='$man_motor', exp_garantia='$exp_garantia', chequeo='$chequeo', idle='$idle', distancia=$distancia, externo='$externo', motor=$motor, id_tableros=$tablero WHERE id_unidad=$id");
		
		$db->query("UPDATE vigia.new_latlng SET id_marcadores=$id_marcador WHERE id_unidad=$id");
		
		//Seteo configuaracion de unidad
		
		$m_tiempo = ($m_tiempo) ? 1 : 0;
		$m_direccion = ($m_direccion) ? 1 : 0;
		$m_estado = ($m_estado) ? 1 : 0;
		$m_velocidad = ($m_velocidad) ? 1 : 0;
		$m_grado = ($m_grado) ? 1 : 0;
		$m_distancia = ($m_distancia) ? 1 : 0;
		$m_horas_motor = ($m_horas_motor) ? 1 : 0;
		$m_volt_externo = ($m_volt_externo) ? 1 : 0;
		$m_volt_respaldo = ($m_volt_respaldo) ? 1 : 0;
		$m_alarma = ($m_alarma) ? 1 : 0;
		$m_tipo_viaje = ($m_tipo_viaje) ? 1 : 0;
		$m_tiempo_gps = ($m_tiempo_gps) ? 1 : 0;
		$m_satelites = ($m_satelites) ? 1 : 0;
		$m_hdop = ($m_hdop) ? 1 : 0;
		$m_latlng = ($m_latlng) ? 1 : 0;
		$m_gsm = ($m_gsm) ? 1 : 0;
		$m_obs_unidad = ($m_obs_unidad) ? 1 : 0;
		$m_des_unidad = ($m_des_unidad) ? 1 : 0;
		$m_num_plato = ($m_num_plato) ? 1 : 0;
		
		$p_tiempo = ($p_tiempo) ? 1 : 0;
		$p_direccion = ($p_direccion) ? 1 : 0;
		$p_estado = ($p_estado) ? 1 : 0;
		$p_velocidad = ($p_velocidad) ? 1 : 0;
		$p_grado = ($p_grado) ? 1 : 0;
		$p_distancia = ($p_distancia) ? 1 : 0;
		$p_horas_motor = ($p_horas_motor) ? 1 : 0;
		$p_volt_externo = ($p_volt_externo) ? 1 : 0;
		$p_volt_respaldo = ($p_volt_respaldo) ? 1 : 0;
		$p_alarma = ($p_alarma) ? 1 : 0;
		$p_tipo_viaje = ($p_tipo_viaje) ? 1 : 0;
		$p_tiempo_gps = ($p_tiempo_gps) ? 1 : 0;
		$p_satelites = ($p_satelites) ? 1 : 0;
		$p_hdop = ($p_hdop) ? 1 : 0;
		$p_latlng = ($p_latlng) ? 1 : 0;
		$p_gsm = ($p_gsm) ? 1 : 0;
		$p_obs_unidad = ($p_obs_unidad) ? 1 : 0;
		$p_des_unidad = ($p_des_unidad) ? 1 : 0;
		$p_num_plato = ($p_num_plato) ? 1 : 0;
		
		
		$db->query("UPDATE vigia.conf_mapa SET m_tiempo=$m_tiempo, m_direccion=$m_direccion, m_estado=$m_estado, m_velocidad=$m_velocidad, m_grado=$m_grado, m_distancia=$m_distancia, m_horas_motor=$m_horas_motor, m_volt_externo=$m_volt_externo, m_volt_respaldo=$m_volt_respaldo, m_alarma=$m_alarma, m_tipo_viaje=$m_tipo_viaje, m_tiempo_gps=$m_tiempo_gps, m_satelites=$m_satelites, m_hdop=$m_hdop, m_latlng=$m_latlng, m_gsm=$m_gsm , m_obs_unidad=$m_obs_unidad, m_des_unidad=$m_des_unidad, m_num_plato=$m_num_plato WHERE id_unidad=$id");
		
		$db->query("UPDATE vigia.conf_panel SET p_tiempo=$p_tiempo, p_direccion=$p_direccion, p_estado=$p_estado, p_velocidad=$p_velocidad, p_grado=$p_grado, p_distancia=$p_distancia, p_horas_motor=$p_horas_motor, p_volt_externo=$p_volt_externo, p_volt_respaldo=$p_volt_respaldo, p_alarma=$p_alarma, p_tipo_viaje=$p_tipo_viaje, p_tiempo_gps=$p_tiempo_gps, p_satelites=$p_satelites, p_hdop=$p_hdop, p_latlng=$p_latlng, p_gsm=$p_gsm, p_obs_unidad=$p_obs_unidad, p_des_unidad=$p_des_unidad, p_num_plato=$p_num_plato WHERE id_unidad=$id");
		
		
		$response['type'] = 'OK:';
		$response['text'] = 'Unidad atualizada correctamente';
	endif;
		
	echo json_encode($response);
	
}

function moveUnit($flota, $subflota){
	global $db, $id;

	if(!$flota && !$subflota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger una flota o subflota';
	elseif($flota && $subflota):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger solo una opcion para mover la unidad';
	elseif($flota):
	
		$sql = $db->query("UPDATE vigia.unidad SET id_flota=$flota WHERE id_unidad=$id");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Unidad asignada a FLOTA correctamente';
	elseif($subflota):

		$sql = $db->query("UPDATE vigia.unidad SET id_flota=$subflota WHERE id_unidad=$id");

		$response['type'] = 'OK:';
		$response['text'] = 'Unidad movida con exito a la SUBFLOTA escogida';
	endif;
		
	echo json_encode($response);	

}

function deleteCompany(){
	global $db, $id;
	
	//Selecciono todas las flotas de la compañia
	$sql = $db->query("SELECT id_flota FROM vigia.flota WHERE compania_id_compania=$id");
	
	if($sql):
		while($r = pg_fetch_object($sql)):
			//Selecciono todas las subflotas
			$sql2 = $db->query("SELECT id_flota FROM vigia.flota WHERE padre=".$r->id_flota);
			//Selecciono todas las unidades de las flotas
			$sql3 = $db->query("SELECT id_unidad FROM vigia.unidad WHERE id_flota=".$r->id_flota);
			
			if($sql2):
				while($d = pg_fetch_object($sql2)):
					//Selecciono todas las unidades de las subflotas
					$sql4 = $db->query("SELECT id_unidad FROM vigia.unidad WHERE id_flota=".$d->id_flota);
					if($sql4):
						while($u = pg_fetch_object($sql4)):
							//Eliminos todas las unidades de las subflotas y sus configuaraciones
							$db->query("UPDATE vigia.geofences SET fk_id_unidad=NULL WHERE fk_id_unidad=".$u->id_unidad);
							$db->query("DELETE FROM vigia.usuario_unidad WHERE unidad_id_unidad=".$u->id_unidad);
							$db->query("DELETE FROM vigia.conf_mapa WHERE id_unidad=".$u->id_unidad);
							$db->query("DELETE FROM vigia.conf_panel WHERE id_unidad=".$u->id_unidad);
							$db->query("DELETE FROM vigia.new_latlng WHERE id_unidad=".$u->id_unidad);
							$db->query("DELETE FROM vigia.unidad WHERE id_unidad=".$u->id_unidad);
						endwhile;
					endif;
					//Elimino todas las subflotas
					$db->query("DELETE FROM vigia.flota WHERE id_flota=".$d->id_flota);
				endwhile;
			endif;
			
			if($sql3):
				while($f = pg_fetch_object($sql3)):
					//Elimino todas las unidades de las flotas y sus configuraciones
					$db->query("UPDATE vigia.geofences SET fk_id_unidad=NULL WHERE fk_id_unidad=".$f->id_unidad);
					$db->query("DELETE FROM vigia.usuario_unidad WHERE unidad_id_unidad=".$f->id_unidad);
					$db->query("DELETE FROM vigia.conf_mapa WHERE id_unidad=".$f->id_unidad);
					$db->query("DELETE FROM vigia.conf_panel WHERE id_unidad=".$f->id_unidad);
					$db->query("DELETE FROM vigia.unidad WHERE id_unidad=".$f->id_unidad);
				endwhile;
			endif;
			//Eliminos todas las flotas
			$db->query("DELETE FROM vigia.flota WHERE id_flota=".$r->id_flota);
		endwhile;
	endif;
	
	$sql5 = $db->query("SELECT id_grupos FROM vigia.grupos WHERE id_compania=$id");
	
	while($g = pg_fetch_object($sql5)):
		$sql6 = $db->query("SELECT id_geofence FROM vigia.geofences WHERE fk_id_grupos=".$g->id_grupos);
		while($k = pg_fetch_object($sql6)):
			$sql7 = $db->query("SELECT id_coordenada FROM vigia.coordenada WHERE geofence_id_geofence=".$k->id_geofence);
			while($j = pg_fetch_object($sql7)):
				$db->query("DELETE FROM vigia.coordenada WHERE id_coordenada=".$j->id_coordenada);
			endwhile;
			$db->query("DELETE FROM vigia.geofences WHERE id_geofence=".$k->id_geofence);
		endwhile;
		$db->query("DELETE FROM vigia.grupos WHERE id_grupos=".$g->id_grupos);
	endwhile;						
		
	
	//Cambio el estado de borrado a TRUe en compañia para tomarla como borrada sin perder los datos
	$db->query("UPDATE vigia.compania SET borrado='TRUE' WHERE id_compania=$id");
	
	$response['type'] = 'OK:';
	$response['text'] = 'Se ha eliminado la compañia completamente';
	
	echo json_encode($response);
	
}

function deleteFleet(){
	global $db, $id;
	
	$sql = $db->query("SELECT id_flota, compania_id_compania FROM vigia.flota WHERE id_flota=$id");
	
	while($r = pg_fetch_object($sql)):
		$sql2 = $db->query("SELECT id_flota FROM vigia.flota WHERE padre=".$r->id_flota);
		$sql3 = $db->query("SELECT id_unidad FROM vigia.unidad WHERE id_flota=".$r->id_flota);
		while($d = pg_fetch_object($sql2)):
			$db->query("UPDATE vigia.flota SET padre=0, compania_id_compania=".$r->compania_id_compania." WHERE padre=".$r->id_flota);
		endwhile;
		while($f = pg_fetch_object($sql3)):
			$db->query("UPDATE vigia.geofences SET fk_id_unidad=NULL WHERE fk_id_unidad=".$f->id_unidad);
			$db->query("DELETE FROM vigia.usuario_unidad WHERE unidad_id_unidad=".$f->id_unidad);
			$db->query("DELETE FROM vigia.conf_mapa WHERE id_unidad=".$f->id_unidad);
			$db->query("DELETE FROM vigia.conf_panel WHERE id_unidad=".$f->id_unidad);
			$db->query("DELETE FROM vigia.new_latlng WHERE id_unidad=".$f->id_unidad);
			$db->query("DELETE FROM vigia.unidad WHERE id_unidad=".$f->id_unidad);
		endwhile;
	endwhile;
	
	$db->query("DELETE FROM vigia.flota WHERE id_flota=$id");
	
	$response['type'] = 'OK:';
	$response['text'] = 'Se eliminado la flota correctamente';
	
	echo json_encode($response);
}

function deleteSubfleet(){
	global $db, $id;
	
	$sql = $db->query("SELECT id_unidad FROM vigia.unidad WHERE id_flota=$id");
	$sql2 = $db->query("SELECT padre FROM vigia.flota WHERE id_flota=$id");
	$r = pg_fetch_object($sql2);
	
	if($sql):
		while($d = pg_fetch_object($sql)):
			$db->query("UPDATE vigia.unidad SET id_flota=".$r->padre." WHERE id_unidad=".$d->id_unidad."");
		endwhile;
	endif;
	
	$db->query("DELETE FROM vigia.flota WHERE id_flota=$id");
	
	$response['type'] = 'OK:';
	$response['text'] = 'Se eliminado la subflota correctamente';
	
	echo json_encode($response);
	
}

function deleteUnit(){
	global $db, $id;
	
	$db->query("UPDATE vigia.geofences SET fk_id_unidad=NULL WHERE fk_id_unidad=$id");
	$db->query("DELETE FROM vigia.usuario_unidad WHERE unidad_id_unidad=$id");
	$db->query("DELETE FROM vigia.conf_mapa WHERE id_unidad=$id");
	$db->query("DELETE FROM vigia.conf_panel WHERE id_unidad=$id");
	$db->query("DELETE FROM vigia.new_latlng WHERE id_unidad=$id");
	$db->query("DELETE FROM vigia.unidad WHERE id_unidad=$id");
	
	$response['type'] = 'OK:';
	$response['text'] = 'Se eliminado la unidad correctamente';
	
	echo json_encode($response);
}

function singleMarker(){
	global $db, $id;
	
	$sql = $db->query("SELECT n.lat, n.lng, m.icono FROM vigia.new_latlng AS n JOIN vigia.marcadores AS m ON(n.id_marcadores=m.id_marcadores) WHERE id_unidad=$id");
	$r = pg_fetch_object($sql);
	
	$response['lat'] = $r->lat;
	$response['lng'] = $r->lng;
	$response['icono'] = $r->icono;
	
	echo json_encode($response);
}

function getImage(){
	global $db, $id;
	
	$sql = $db->query("SELECT imagen FROM vigia.foto WHERE id_unidad=".$id);
	$r = pg_fetch_object($sql);
	
	$response['image'] = $r->imagen;
	
	echo json_encode($response);
	
}

function eraseImage(){
	global $db, $id;
	
	$sql = $db->query("SELECT imagen FROM vigia.foto WHERE id_unidad=$id");
	$r = pg_fetch_object($sql);
	deleteImages($r->imagen);
	$db->query("DELETE FROM vigia.foto WHERE id_unidad=$id");
	
	$response['del'] = 'erase';
	
	echo json_encode($response);
	
}

function actualizarUnit(){
	global $db, $id;
	
	$sql= $db->query("SELECT imei FROM vigia.unidad WHERE id_unidad=$id");
	$r = pg_fetch_object($sql);

	$db->query("UPDATE socket.status_unit SET actualizar='TRUE'  WHERE imei =".$r->imei);
	
	$response['type'] = 'OK:';
	$response['text'] = 'Unidad actualizada';
	
	echo json_encode($response);
}

