<? 
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';

	$markers=$db->query("SELECT u.id_unidad, n.lat, n.lng, m.icono, s.status, s.sensor, s.motor FROM vigia.unidad AS u JOIN vigia.new_latlng AS n ON (u.id_unidad=n.id_unidad) JOIN vigia.marcadores AS m ON (n.id_marcadores=m.id_marcadores) JOIN socket.status_unit AS s ON (u.imei=s.imei)");
    
	while($unity = pg_fetch_object($markers)):
		$mark[] = array("id_unidad"=>$unity->id_unidad,"lng"=>$unity->lng/1000000,"lat"=>$unity->lat/1000000,"unidad"=>$unity->unidad,"icono"=>$unity->icono, "status"=>$unity->status, "sensor"=>$unity->sensor, "motor"=>$unity->motor);
    endwhile;
    
	echo json_encode($mark);
?>