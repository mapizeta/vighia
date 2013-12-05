<? 
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
 
$op = getXss($_REQUEST, 'op');
$id_unidad = getXss($_REQUEST, 'id_unidad');

switch ($op) {
	case '1':
		progesbar();
		break;
		
		case '2':
			unidad();
			break;
			
		case '3':
          	mapa();
			break;

		case '4':
			latlng();
			break;

		case '5':
			panel();
			break;
			
		case 'single-unit':
			singleUnit();
			break;

		case 'unit-table':
			unidadesTable();
			break;

}

function progresbar(){
	global $db;
	
		    $progreso=$db->query("SELECT * FROM vigia.progresbar");
		    $progres = pg_fetch_object($progreso);
			
		    echo $progreso = $progres->progreso;

			if($progres->progreso >= 1 ):
			
				$actualizar=$db->query("UPDATE vigia.progresbar SET progreso = progreso-1  ");
				$actualizar1 = pg_fetch_object($actualizar);
				
		    endif;
	
}

function unidad(){
	global $db, $id_unidad;
	
			$res_unidad=$db->query("SELECT * FROM vigia.trama AS t JOIN vigia.unidad AS u ON (id_unidad=unidad_id_unidad) JOIN socket.status_unit AS s ON (u.imei=s.imei) WHERE u.id_unidad=".$id_unidad." ORDER BY id_trama DESC LIMIT 1");
			$unity = pg_fetch_object($res_unidad);
			
			$h1 = explode(' ', $unity->timer);
			$h2 = explode('.', $h1[1]);
						
			if($unity->unidad_id_unidad == $id_unidad):
				echo json_encode( array( "odometro"=>$unity->odometro,"satelite"=>$unity->satelites,"voltaje"=>$unity->volt_ext,"voltajebateria"=>$unity->volt_bat,"diesel"=>$unity->comb_lvl,"velocidad"=>$unity->veloc,"calidad_gsm"=>$unity->calidad_gsm, "lat"=>$unity->lat, "lng"=>$unity->lng, "grado"=>$unity->grado, "calidad_gsm"=>$unity->calidad_gsm, "gpstime"=>$unity->gpstime, "patente"=>$unity->patente, "des_unidad"=>$unity->descripcion, "obs_unidad"=>$unity->observaciones, "timer"=>$h2[0], "status"=>$unity->status, "sensor"=>$unity->sensor, "unidad_est"=>$unity->unidad_est, "motor"=>$unity->motor, "time_status"=>$unity->time_status));
			endif;
	
}

function mapa(){
	global $db, $id_unidad;
	
	$conf_mapa=$db->query("SELECT cm.m_tiempo, cm.m_direccion, cm.m_estado, cm.m_velocidad, cm.m_grado, cm.m_distancia, cm.m_horas_motor, cm.m_volt_externo, cm.m_volt_respaldo, cm.m_alarma, cm.m_tipo_viaje, cm.m_tiempo_gps, cm.m_satelites, cm.m_hdop, cm.m_latlng, cm.m_gsm, cm.m_obs_unidad, cm.m_des_unidad, cm.m_num_plato, u.id_unidad, u.nombre FROM vigia.conf_mapa as cm, vigia.unidad as u WHERE u.id_unidad = cm.id_unidad  AND u.id_unidad = ".$id_unidad);
	
	$unity = pg_fetch_object($conf_mapa);
	echo json_encode($unity);
	
}

function latlng(){
	global $db, $id_unidad;
	
		$sql = $db->query("SELECT n.lng as lng , n.lat as lat, m.icono as icono FROM vigia.new_latlng n , vigia.marcadores m WHERE n.id_marcadores = m.id_marcadores AND id_unidad=".$id_unidad);
		$latlng = pg_fetch_object($sql);
		
		echo json_encode($latlng);
	
}

function panel(){
	global $db, $id_unidad;
	
		$conf_panel=$db->query("SELECT * FROM vigia.conf_panel WHERE id_unidad = ".$id_unidad);
		$panel = pg_fetch_object($conf_panel);
		echo json_encode($panel);
	
}

function singleUnit(){
	global $db, $id_unidad;
	
	$sql = $db->query("SELECT n.lat, n.lng, m.icono, s.status, s.sensor, s.motor FROM vigia.unidad AS u JOIN vigia.new_latlng AS n ON (u.id_unidad=n.id_unidad) JOIN vigia.marcadores AS m ON (n.id_marcadores=m.id_marcadores) JOIN socket.status_unit AS s ON (u.imei=s.imei) WHERE n.id_unidad=$id_unidad");
	$r = pg_fetch_object($sql);
	
	$response['lat'] = $r->lat/1000000;
	$response['lng'] = $r->lng/1000000;
	$response['icono'] = $r->icono;
	$response['status'] = $r->status;
	$response['sensor'] = $r->sensor;
	$response['motor'] = $r->motor;
	$response['id_unidad'] = $id_unidad;
	
	echo json_encode($response);
	
}

function unidadesTable(){
	global $db;

	$sql = $db->query("SELECT MAX(t.id_trama) as trama, u.id_unidad, u.nombre, u.patente, u.conductor, f.nombre_flota, ll.lng, ll.lat, s.status FROM vigia.unidad u LEFT JOIN vigia.flota f ON u.id_flota = f.id_flota LEFT JOIN vigia.trama t ON u.id_unidad = t.unidad_id_unidad LEFT JOIN vigia.new_latlng ll ON u.id_unidad = ll.id_unidad LEFT JOIN socket.status_unit s on u.imei = s.imei  GROUP BY u.id_unidad, u.nombre, u.unidad_est, u.patente, u.conductor, f.nombre_flota, ll.lng, ll.lat, s.status ");
	while($r = pg_fetch_object($sql)){
		$sql2 = $db->query("SELECT gpstime,  unidad_id_unidad FROM vigia.trama WHERE id_trama =".$r->trama);
		$r2 = pg_fetch_object($sql2);
		$resultado[] = array(
			"id"=>$result['id_unidad'] = $r->id_unidad, 
			"nombre"=>$result['nombre'] = $r->nombre, 
			"unidad_est"=>$result['status'] = $r->status, 
			"patente"=>$result['patente'] = $r->patente,
			"conductor"=>$result['conductor'] = $r->conductor, 
			"nombre_flota"=>$result['nombre_flota'] = $r->nombre_flota,
			"lng"=>$result['lng'] = $r->lng,
			"lat"=>$result['lat'] = $r->lat,
			"tiempo"=>$result['gpstime'] = $r2->gpstime,
			);
	}echo json_encode($resultado);
}
	







