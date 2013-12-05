<? //UPDATE
require_once "../config.php";
require_once ABS_PATH.'/include/conect.php';
include "usuario.php";

$fecha_actual = date('Y-m-d');
$id=$_GET["id"];

//UPDATE USUARIO
if($id==1){
	
	$md5pass = md5 ( $pass );
	$usr = new Usuario();
	$usr->set_id($id_usuario);
	$usr->set_user($user);
	$usr->set_pass($md5pass);
	$usr->set_nombre($nombre);
	$usr->set_telefono($telefono);
	$usr->set_mail($email);
	$usr->set_compania($compania);
	$usr->set_perfil($perfil);
	if($activo == "on")
		$usr->set_activo('TRUE');
	else
		$usr->set_activo('FALSE');

	$idd = $usr->updateBD($db);
	header("location:../users/form_asignar_unidad.php?idusr=enviarid!");
}

//ELIMINAR USUARIO

if($id==2){

	$sql="UPDATE vigia.usuario SET borrado=true WHERE id_usuario=$id_usuario";
	//echo $sql;
	$id_last = $db->query($sql);
	
	header("location:../users/");
}
?>