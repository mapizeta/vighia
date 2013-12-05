<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
//Retorna si menu pertenece a usuario
function getPermisoMenu($perfil,$menu){
global $db;
		
		$sql="SELECT ".$menu." FROM vigia.permisos_menu WHERE id_permisos_menu=".$perfil." AND ".$menu."=TRUE";
		$res = $db->query($sql);
		$row = pg_fetch_object($res);
		if($row->$menu)
			return TRUE;
		else
			return FALSE;
}

//retorna true si la unidad pertenece a usuario false en caso contrario
function getUserUnidad($usuario, $unidad){
	global $db;
		$sql="SELECT id_unidad FROM vigia.permisos_arbol WHERE id_usuario=".$usuario;
		$res = $db->query($sql);
		$row = pg_fetch_object($res);
	
		$array=genera_array($row->id_unidad);
		
		foreach ($array as &$valor)		
			if($unidad == $valor)
				return TRUE;

	return FALSE;
}
//Verifica usuario tiene compañia devuelve false en caso contrario
function getUserCompany($usuario, $compania){
	global $db;
		$sql="SELECT id_compania FROM vigia.permisos_arbol WHERE id_usuario=".$usuario;
		$res= $db->query($sql);
		$row = pg_fetch_object($res);
		
		$array=genera_array($row->id_compania);

		foreach ($array as &$valor)
			if($compania == $valor)
				return TRUE;

	return FALSE;
		
}
function getUserFlota($usuario, $flota){
	global $db;
		$sql="SELECT id_flotas FROM vigia.permisos_arbol WHERE id_usuario=".$usuario;
		$res= $db->query($sql);
		$row = pg_fetch_object($res);
		
		$array=genera_array($row->id_flotas);

		foreach ($array as &$valor)
			if($flota == $valor)
				return TRUE;

	return FALSE;
		
}

function getUserSubFlota($usuario, $subflota){
	global $db;
		$sql="SELECT id_subflotas FROM vigia.permisos_arbol WHERE id_usuario=".$usuario;
		$res= $db->query($sql);
		$row = pg_fetch_object($res);
		
		$array=genera_array($row->id_subflotas);

		foreach ($array as &$valor)
			if($subflota == $valor)
				return TRUE;

	return FALSE;
		
}

//obtiene la flota de la unidad
function getFlotaUnidad($id_unidad){
global $db;
	
	$sql = $db->query("SELECT id_flota FROM vigia.unidad WHERE id_unidad=$id_unidad");
	
	$r = pg_fetch_object($sql);
	
	return $r->id_flota;
}

function getUserActivo($idU){
	global $db;
	
	$sql = $db->query("SELECT activo FROM vigia.usuario WHERE id_usuario=$idU AND activo = 'TRUE'");
	
	$r = pg_fetch_object($sql);
	
	return $r->activo;
}

function returnChecked($boleano){
if($boleano)
	return 'checked=""';
else
	return "";
}

function returnSelected($boleano){
if($boleano)
	return 'selected';
else
	return "";
}

function getCompanias($where){
	global $db;

	$sql = $db->query("SELECT * FROM vigia.compania WHERE borrado=false $where ORDER BY id_compania");

	return $sql;
}


function getCompany($idC){
	global $db;

	$sql = $db->query("SELECT id_compania, compania FROM vigia.compania WHERE id_compania IN (".$idC.")");

	return $sql;
}

function getCompanyName($idC){
	global $db;

	$sql = $db->query("SELECT compania FROM vigia.compania WHERE id_compania IN (".$idC.")");

	$row = pg_fetch_result($sql, 0, 0);

	return $row;
}

function getFleet($idC, $id_flotas){
	global $db;
	
	if($id_flotas != 0) $where = "AND id_flota IN (".$id_flotas.")";
	
	$sql = $db->query("SELECT * FROM vigia.flota WHERE compania_id_compania=".$idC." AND padre=0 AND borrado='FALSE' $where ORDER BY id_flota");
	
	return $sql;
}

function getSubFleet($idF, $id_subflotas){
	global $db;
	
	if($id_subflotas != 0) $where = "AND id_flota IN (".$id_subflotas.")";

	$sql2 = $db->query("SELECT * FROM vigia.flota WHERE padre=".$idF." AND borrado='FALSE' $where ORDER BY id_flota");

	return $sql2;
}

function getUnitFleet($idF, $id_unidad){
	global $db;
	
	if($id_unidad != 0) $where = "AND id_unidad IN (".$id_unidad.")";

	$sql3 = $db->query("SELECT id_unidad, nombre, id_marcadores FROM vigia.unidad  WHERE  id_flota = ".$idF." AND borrado='FALSE' $where ORDER BY id_unidad");

	return $sql3;
}

function getGroup($id){
	global $db;
	
	$sql = $db->query("SELECT * FROM vigia.grupos WHERE id_compania=$id ORDER BY id_grupos");
	
	return $sql;
}

function getGeofence($id){
	global $db;
	
	$sql = $db->query("SELECT * FROM vigia.geofences WHERE fk_id_grupos=$id ORDER BY id_geofence");
	
	return $sql;
}

function getIcon($id_marcadores){
	global $db;

	$sql = $db->query("SELECT icono FROM vigia.marcadores WHERE id_marcadores=".$id_marcadores);
	$r = pg_fetch_object($sql);
	
	return $r->icono;
}

function deleteImages($imagen){
	$rutas = array('images_320', 'images_800');
	foreach($rutas as $ruta){
		$img = ABS_PATH.'/picture/'.$ruta.'/'.$imagen;
		if(file_exists($img)) if(is_file($img)) unlink($img);
	}
}

function get_lat_lng($id)
{
	global $db;

	$sql = $db->query("SELECT lat, lng FROM vigia.trama WHERE unidad_id_unidad=".$id." ORDER BY timer DESC LIMIT 1");
	return $sql;
}

function formatDate($date){
	
	if($date):
		$fecha = explode('-', $date);
			
		return $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
	endif;

}

function backDate($date){
	
	if($date):
		$f = explode(' ', $date);
		
		switch($f[2]){
			case 'Enero': $m = '01'; break;
			case 'Febrero': $m = '02'; break;
			case 'Marzo': $m = '03'; break;
			case 'Abril': $m = '04'; break;
			case 'Mayo': $m = '05'; break;
			case 'Junio': $m = '06'; break;
			case 'Julio': $m = '07'; break;
			case 'Agosto': $m = '08'; break;
			case 'Septiembre': $m = '09'; break;
			case 'Octubre': $m = '10'; break;
			case 'Noviembre': $m = '11'; break;
			case 'Diciembre': $m = '12'; break;
		}
	endif;
	
	return $f[4].'-'.$m.'-'.$f[0];
	
}

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
function guarda_coord_circle($coord, $geofence){
	global $db;
	
	$coord = explode(",", $coord);
	$sql = "INSERT INTO vigia.coordenada (lat, lng, rad, geofence_id_geofence) VALUES (".$coord[0].", ".$coord[1].", ".$coord[2].", ".$geofence.")";
	
	$res = $db->query($sql);

}

//Selecciona todas las subflotas de una compañia
function selectFleetCompany($id_flota, $id_compania){
	global $db;
	
	$sql = $db->query("SELECT padre FROM vigia.flota WHERE id_flota=$id_flota");
	$r = pg_fetch_object($sql);
	
	$sql2 = $db->query("SELECT id_flota FROM vigia.flota WHERE compania_id_compania=$id_compania AND id_flota=".$r->padre);
	$d = pg_fetch_object($sql2);
	
	if($d->id_flota) return 'yes';
}

function genera_array($campo){
$array = explode(", ", $campo);
return $array;
}

//Crea un string a partir de un array para el arbol permisos
function crea_string_array($ids){
	//se asigna el primer id de compania
	$string=$ids[0];

	//si tiene mas de un id de compania se crea el string con los otros ids
	if($ids[1])
	{
		for ($i=1;$i<count($ids);$i++)    
		{     
		$string = $string.", ".$ids[$i];    
		} 
	}
	return $string;
}
//retorna string con el nombre de los campos solicitados
function impr_campo_permisos($usuario, $campo){
global $db;
$sql1="SELECT id_".$campo." FROM vigia.permisos_arbol WHERE id_usuario=".$usuario;
$res1=$db->query($sql1);
$row1 = pg_fetch_result($res1, 0, 0);

if($row1 == 0)

	return "SUPER ADMINISTRADOR";
else{

$sql2="SELECT ".$campo." FROM vigia.".$campo." WHERE id_".$campo." IN (".$row1.")";

$res2=$db->query($sql2);
$string=pg_fetch_result($res2, 0, 0);
$num = pg_num_rows($res2);
if($num>1){
for($i=1;$i<$num;$i++)
{
$row2 = pg_fetch_result($res2, $i, 0);
$string =$string.", ".$row2;
}
}
return $string;
}
}

//*************************FUNCIONES IMPRIMIR GEOCERCAS*****************************************
function imprimePolygon($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	echo "var polygono".$cont." = [\n";
	while ($row = pg_fetch_object($res)) 
	        {
		 echo "\n new google.maps.LatLng(".$row->lat.", ".$row->lng.")";
		 if($row)
		 	echo ",";
		    }
	echo "];\n";

	echo "var Poligono".$cont." = new google.maps.Polygon({
		    paths: polygono".$cont.",
		    strokeColor: \"#22A9E3\",
		    strokeOpacity: 0.8,
		    strokeWeight: 3,
		    fillColor: \"#22A9E3\",
		    fillOpacity: 0.35
		  });\n";
		  
		  echo "Poligono".$cont.".setMap(map);\n";

}

function imprimePolyline($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	echo "var ruta".$cont." = [\n";
	while ($row = pg_fetch_object($res)) 
	        {
		 echo "\n new google.maps.LatLng(".$row->lat.", ".$row->lng.")";
		 if($row)
		 	echo ",";
		    }
	echo "];\n";

	echo "var PolyLine".$cont." = new google.maps.Polyline({
		    path: ruta".$cont.",
		    strokeColor: \"#FE2E2E\",
	   		strokeOpacity: 0.8,
	    	strokeWeight: 3
		  });\n";
		  
		  echo "PolyLine".$cont.".setMap(map);\n";

}
function imprimeMarker($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;
	$sql = $db->query("SELECT icono FROM vigia.puntos_interes AS pt JOIN vigia.geofences AS g ON pt.id_puntos_interes=g.id_puntos_interes WHERE g.id_geofence=".$geofence);
	$i = pg_fetch_object($sql);

	$res=$db->query($consulta);
	$coor = pg_fetch_object($res);
	echo "var punto".$cont." = new google.maps.LatLng(".$coor->lat.", ".$coor->lng.");\n";
	
	echo "var marker".$cont." = new google.maps.Marker({
		    position: punto".$cont.",";
	if($i->icono):
	echo	"icon: '../images/points/".$i->icono."'";
	endif;
	echo	"});\n";
		
	echo "marker".$cont.".setMap(map);\n";
	
}
function imprimeCircle($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	$coor = pg_fetch_object($res);
	echo "var circulocoord".$cont." = new google.maps.LatLng(".$coor->lat.", ".$coor->lng.");\n";
	
	echo "var circle".$cont." = new google.maps.Circle({
		    center: circulocoord".$cont.",
			radius: ".$coor->rad.",
		    strokeColor: \"#FE9A2E\",
		    strokeOpacity: 0.8,
		    strokeWeight: 3,
		    fillColor: \"#FE9A2E\",
		    fillOpacity: 0.35
		});\n";
		
	echo "circle".$cont.".setMap(map);\n";
	
}

function imprime_geofence($geofence,$tipo,$cont){

switch ($tipo) {	

	default:
		break;
		
	case 1:
		imprimeMarker($geofence,$cont);
		break;
		
	case 2:
		imprimePolygon($geofence,$cont);
		break;
				
	case 3:
		imprimeCircle($geofence,$cont);
		break;
		
	case 4:
		imprimePolyline($geofence,$cont);
		break;
}
}
//Retorna la primera coordenada de una geocerca 
function primera_coord($geofence){
global $db;

$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence." LIMIT 1";
$res=$db->query($consulta);
$coor = pg_fetch_object($res);

return $coor->lat.", ".$coor->lng;

}