<?
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';
require_once ABS_PATH.'/users/usuario.php';
require_once ABS_PATH.'/include/security/XSS.php';

$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');

switch ($task) {
		
	case 'save-user':
		$nombre = getXss($_REQUEST, 'nombre');
		$telefono = getXss($_REQUEST, 'telefono');
		$email = getXss($_REQUEST, 'email');
		$user = getXss($_REQUEST, 'user');
		$pass = getXss($_REQUEST, 'pass');
		$compania = getXss($_REQUEST, 'compania');
		$perfil = getXss($_REQUEST, 'perfil');
		saveUser($id, $nombre, $telefono, $email, $user, $pass, $pass, $compania, $perfil);
		break;
		
	case 'save-asign-fleet':
		$unidades1=getXss($_REQUEST, 'unidades1');
		$unidades2=getXss($_REQUEST, 'unidades2');
		$flota=getXss($_REQUEST, 'flota');
		$subflota=getXss($_REQUEST, 'subflota');
		saveAsignFleet($unidades1, $unidades2, $flota, $subflota);
		break;
		
	case 'create-profile':
		$perfil = getXss($_REQUEST, 'perfil');
		$SU = getXss($_REQUEST, 'SU');
		createProfile($perfil , $SU);
		break;
		
	case 'update-profile':
		$unit = getXss($_REQUEST, 'unit');
		$tp = getXss($_REQUEST, 'tp');
		$options = getXss($_REQUEST, 'options');
		updateProfile($unit, $tp, $options);
		break;
		
	case 'update-user':
		$nombre = getXss($_REQUEST, 'nombre');
		$telefono = getXss($_REQUEST, 'telefono');
		$email = getXss($_REQUEST, 'email');
		$user = getXss($_REQUEST, 'user');
		$compania = getXss($_REQUEST, 'compania');
		$perfil = getXss($_REQUEST, 'perfil');
		$activo = getXss($_REQUEST, 'activo');
		updateUser($nombre, $telefono, $email, $user, $compania, $perfil, $activo);
		break;
		
	case 'delete-user':
		deleteUser();
		break;
		
	case 'update-pass':
		$pass1 = getXss($_REQUEST, 'pass1');
		$pass2 = getXss($_REQUEST, 'pass2');
		updatePass($pass1, $pass2);
		break;
		
	case 'delete-profile':
		deleteProfile();
		break;	
}


function saveUser($id, $nombre, $telefono, $email, $user, $pass, $pass, $compania, $perfil){
	global $db;
	
	$sql = $db->query("SELECT usuario FROM vigia.usuario WHERE usuario='$user'");
	$r = pg_fetch_object($sql);
	
	if(!$nombre || !$user || !$pass || !$perfil):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar campos obligatorios';
	elseif($r->usuario):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El nombre de usuario ya esta en uso';
	else:
		$fecha_actual = date('Y-m-d');
		//INGRESO USUARIO
		if($id==1){
		$md5pass = md5 ($pass);
		$usr = new Usuario();
		$usr->set_user($user);
		$usr->set_pass($md5pass);
		$usr->set_nombre($nombre);
		$usr->set_telefono($telefono);
		$usr->set_mail($email);
		$usr->set_fecha($fecha_actual);
		$usr->set_perfil($perfil);
		$idd = $usr->insertBD($db);
		
		$sql1 = $db->query("SELECT MAX(id_usuario) AS id_usuario FROM vigia.usuario");
		$r = pg_fetch_object($sql1);
		//Se asignan las compañias dentro de los permisos
		$companias = crea_string_companias($compania);
		$db->query("INSERT INTO vigia.permisos_arbol (id_compania, id_flotas, id_subflotas, id_unidad, id_usuario) VALUES ('".$companias."', 'null', 'null', 'null', ".$r->id_usuario.")");
		
		$response['type'] = 'OK:';
		$response['id'] = $r->id_usuario;
		$response['companias'] = $companias;
		

		}
	endif;
	
	echo json_encode($response);
}
function saveAsignFleet($unidades1, $unidades2, $flota, $subflota){
	global $db, $id;
		$uni1 = crea_string_array($unidades1);
		$uni2 = crea_string_array($unidades2);
		if($flota)
		$flo = "id_flotas=".crea_string_array($flota);
		if($subflota)
		$subflo = ", id_subflotas=".crea_string_array($subflota);

		$sql="UPDATE vigia.permisos_arbol SET ".$flo.$subflo.", id_unidad=".$uni1.$uni2." WHERE id_usuario=".$id;
		//$db->query("UPDATE vigia.permisos_arbol SET id_flotas, id_subflotas, id_unidad WHERE id_usuario=".$id);	
		
		$response['type'] = 'OK:';
		$response['text'] = $sql;
		
	echo json_encode($response);
}
function createProfile($perfil, $SU){
	global $db;
	
	$sql = $db->query("SELECT nombre FROM vigia.perfil WHERE nombre='$perfil'");
	$r = pg_fetch_object($sql);
	
	if(!$perfil):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar un nombre para el Perfil';
	elseif($r->nombre):
		$response['type'] = 'ERROR:';
		$response['text'] = 'El perfil que intenta crear ya existe';
	elseif(!$SU):
		$sql = "INSERT INTO vigia.perfil (nombre, verflota) VALUES ('$perfil', '0')";
		$res = $db->query($sql);
		
		$sql2 = "SELECT id_perfil FROM vigia.perfil ORDER BY id_perfil DESC LIMIT 1";
		$res = $db->query($sql2);
		$row = pg_fetch_object($res);
		
		$response['type'] = 'succes';
		$response['text'] = 'Usuario creado correctamente';
		$response['id'] = $row->id_perfil;
		
	else:
		$sql = "INSERT INTO vigia.perfil (nombre, verflota) VALUES ('$perfil', '$SU')";
		$res = $db->query($sql);
		
		$sql2 = "SELECT id_perfil FROM vigia.perfil ORDER BY id_perfil DESC LIMIT 1";
		$res = $db->query($sql2);
		$row = pg_fetch_object($res);
		
		$response['type'] = 'succes';
		$response['id'] = $row->id_perfil;
	endif;
		
	echo json_encode($response);
}
function updateProfile($unit, $tp, $options){
	global $db;
	
	if(!$options):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe escoger al menos 1 permiso';
	elseif($tp == 'update'):
		foreach ($options as &$valor){
			$sql="UPDATE vigia.permisos_menu SET ".$valor."=TRUE WHERE id_permisos_menu=".$unit;
			$res = $db->query($sql);
		}
				
		$response['type'] = 'OK:';
		$response['text'] = 'Datos guardados correctamente'.$_POST;

	endif;
	
	echo json_encode($response);
}

function updateUser($nombre, $telefono, $email, $user, $compania, $perfil, $activo){
	global $id, $db;
	
	if(!$nombre || !$user || !$compania || !$perfil):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar campos obligatorios';
	else:
	
		$usr = new Usuario();
		$usr->set_id($id);
		$usr->set_user($user);
		$usr->set_nombre($nombre);
		$usr->set_telefono($telefono);
		$usr->set_mail($email);
		//$usr->set_compania($compania);
		$usr->set_perfil($perfil);
		if($activo == "on")
			$usr->set_activo('TRUE');
		else
			$usr->set_activo('FALSE');
	
		$idd = $usr->updateBD($db);
		$companias = crea_string_array($compania);
		$db->query("UPDATE vigia.permisos_arbol SET id_compania='".$companias."' WHERE id_usuario=".$id);

		$response['type'] = 'OK:';
		$response['id'] = $id;
		$response['companias'] = $companias;
		
	endif;
	
	echo json_encode($response);
}

function deleteUser(){
	global $id, $db;
	
	$db->query("DELETE FROM vigia.usuario_unidad WHERE usuario_id_usuario=$id");
	$db->query("DELETE FROM vigia.permisos_arbol WHERE id_usuario=$id");
	$db->query("DELETE FROM vigia.usuario WHERE id_usuario=$id");
	
	header("location:".SITE_PATH."/users/");
		
}

function updatePass($pass1, $pass2){
	global $db, $id;
	
	$p1 = md5($pass1);
	$p2 = md5($pass2);
	
	if(!$pass1 || !$pass2):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Debe ingresar contraseñas';
	elseif($p1 != $p2):
		$response['type'] = 'ERROR:';
		$response['text'] = 'Las contraseñas son distintas';
	else:
	
		$db->query("UPDATE vigia.usuario SET pass='$p1' WHERE id_usuario=$id");
		
		$response['type'] = 'OK:';
		$response['text'] = 'Contraseña cambiada correctamente';
		
	endif;

	echo json_encode($response);
}

function deleteProfile(){
	global $id, $db;
	
	$db->query("UPDATE vigia.usuario SET perfil_id_perfil=1 WHERE perfil_id_perfil=$id");
	$db->query("DELETE FROM vigia.menu_has_perfil WHERE perfil_id_perfil = $id");
	$db->query("DELETE FROM vigia.perfil WHERE id_perfil=$id");
		
	$response['type'] = 'OK:';
	$response['text'] = 'Perfil borrado correctamente';
		
	echo json_encode($response);
}