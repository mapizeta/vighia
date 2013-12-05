<?
//$sql = "INSERT INTO vigia.perfil (nombre, verflota) VALUES ('$perfil', '$SU')";
	//	$res = $db->query($sql);
include "config.php";

function obtener_id_tipo($cadena){
global $db;
$consulta="SELECT id_tipo FROM vigia.tipo WHERE nombre='".$cadena."'";
$res=$db->query($consulta);
$coor = pg_fetch_object($res);
return $coor->id_tipo;
}

function ultima_geofence(){
global $db;
$consulta="SELECT MAX(id_geofence) AS id_geocerca FROM vigia.geofences";
$res=$db->query($consulta);
$id_geofence = pg_fetch_object($res);
return $id_geofence->id_geocerca;
}

function guarda_coord($coord, $geofence){
global $db;
$coord = explode(",", $coord);

$sql = "INSERT INTO vigia.coordenada (lat, lng, geofence_id_geofence) VALUES (".$coord[0].", ".$coord[1].", ".$geofence.")";

$res = $db->query($sql);

}

$action = $_POST["action"];
$coord = explode(";", $action);

$sql = "INSERT INTO vigia.geofences (nombre, descripcion, fk_id_tipo, fk_id_unidad) VALUES ('".$nombre."', '".$descripcion."', ".obtener_id_tipo($coord[0]).", ".$id_unidad.")";
$res = $db->query($sql);

$id_geofence=ultima_geofence();
$pos = strpos($coord[1], '((');

//condicion si es circunferencia
if($pos !== false){
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

echo "Geocerca Guardada.";
?>