<?php
require_once 'include/conect.php';
require_once 'include/security/XSS.php';

$task = getXss($_REQUEST, 'task');

switch ($task) {
	
	default:
		index($task);
		break;
	
	case 'login':
		$u = getXss($_REQUEST, 'usuario');
		$p = getXss($_REQUEST, 'pass');
		$s = getXss($_REQUEST, 'sesion_id');
		Login($u, $p);
		break;
}

function index($task){
	$response['type'] = 'error';
	$response['text'] = 'No tiene permisos';
	
	echo json_encode($response);
	
}
		
function Login($u, $p, $s=NULL){
	global $db;
	
	if(!$u || $u == 'Usuario'): 
		$response['type'] = 'error';
		$response['text'] = 'Debe ingresar usuario';
	elseif(!$p || $p == 'Contraseña'): 
		$response['type'] = 'error';
		$response['text'] = 'Debe ingresar contraseña';
	else:
		
		//$p = sha1($p);
		$md5pass = md5 ( $p );
		$sql = "SELECT * FROM vigia.usuario WHERE pass = '$md5pass' AND usuario = '$u'";
		$d = $db->query($sql);
		$r = pg_fetch_assoc($d);
		
		if($u == $r['usuario'] && $md5pass == $r['pass']):
			session_start();
			$_SESSION["access"] = date("Y-n-j H:i:s");
			$_SESSION["userid"] = $r['id_usuario'];
			$_SESSION["user"] = $r['usuario'];
			$_SESSION["profile"] = $r['perfil_id_perfil'];
			$_SESSION["name"] = $r['nombre'];		
			$_SESSION['sid'] = session_id();
			$sql2 = ("UPDATE vigia.usuario SET sid= '".session_id()."' WHERE id_usuario= ".$r['id_usuario']."");
			$db->query($sql2);
			
			if(isset($s)){
				setcookie("cookname", $r['usuario'], time()+60*60*24*100, "/");
				setcookie("cookpass", $r['pass'], time()+60*60*24*100, "/");
			}
			
			$response['type'] = 'succes';

	
		else: 
			$response['type'] = 'error';
			$response['text'] = 'Usuario y contraseña invalidos';
		endif;
	endif;
	
	echo json_encode($response);

}