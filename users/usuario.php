<?
/*
host:vpro.no-ip.biz
usuario:kusko
alpha242526
userftp:rowsis
gama242526
*/
?>
<?
class Usuario {
	private $_id;
	private $_user;
	private $_pass;
	private $_nombre;
	private $_telefono;
	private $_compania;
	private $_mail;
	private $_fecha;
	private $_activo;
	private $_borrado;
	private $_perfil;
	 
//inserta registros en base de datos
public function insertBD($db) {
$sql="INSERT INTO vigia.usuario (usuario, pass, nombre, telefono, email, fecha_reg, perfil_id_perfil) VALUES ('$this->_user', '$this->_pass', '$this->_nombre', '$this->_telefono', '$this->_mail', '$this->_fecha', $this->_perfil)";
//echo $sql;
$id_last = $db->insert($sql);

return $id_last;
}

public function updateBD($db){
$sql="UPDATE vigia.usuario SET usuario='$this->_user', nombre='$this->_nombre', telefono='$this->_telefono', email='$this->_mail', perfil_id_perfil=$this->_perfil, activo='$this->_activo' WHERE id_usuario=$this->_id";
//echo $sql;
$id_last = $db->query($sql);
}

public function usuarioxID($id, $db) {
	
$sql="SELECT * FROM vigia.usuario WHERE id_usuario = ".$id;
$res = $db->query($sql);
$row = pg_fetch_object($res);

 $this->_id = $row->id_usuario;  
 $this->_user = $row->usuario;  
 $this->_nombre = $row->nombre; 
 $this->_telefono = $row->telefono; 
 $this->_mail = $row->email;  
 $this->_fecha = $row->fecha_reg;  
 $this->_perfil = $row->perfil_id_perfil;  
 //$this->_compania = compania($db); 

return $this;

}

public function compania($db) {
	$sql="SELECT id_compania FROM vigia.permisos_arbol WHERE id_usuario=".$this->_id;
$res = $db->query($sql);
$row = pg_fetch_object($res);

return $row->id_compania;
}

public function perfil($db) {
	$sql="SELECT perfil.nombre FROM vigia.usuario, vigia.perfil WHERE vigia.perfil.id_perfil=vigia.usuario.perfil_id_perfil AND id_usuario=".$this->_id;
$res = $db->query($sql);
$row = pg_fetch_object($res);

return $row->nombre;
}

//setter y getter	
public function get_user() { return $this->_user; } 
public function get_pass() { return $this->_pass; } 
public function get_nombre() { return $this->_nombre; } 
public function get_telefono() { return $this->_telefono; } 
public function get_mail() { return $this->_mail; } 
public function get_fecha() { return $this->_fecha; } 
public function get_activo() { return $this->_activo; } 
public function get_borrado() { return $this->_borrado; } 
public function get_perfil() { return $this->_perfil; }
public function get_compania() { return $this->_compania; } 
public function set_user($x) { $this->_user = $x; } 
public function set_pass($x) { $this->_pass = $x; } 
public function set_nombre($x) { $this->_nombre = $x; } 
public function set_telefono($x) { $this->_telefono = $x; } 
public function set_mail($x) { $this->_mail = $x; } 
public function set_fecha($x) { $this->_fecha = $x; } 
public function set_activo($x) { $this->_activo = $x; } 
public function set_borrado($x) { $this->_borrado = $x; }  
public function set_perfil($x) { $this->_perfil = $x; } 
public function set_id($x) { $this->_id = $x; }
public function set_compania($x) { $this->_compania = $x; }

}
?>