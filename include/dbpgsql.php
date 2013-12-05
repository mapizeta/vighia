<?
/*
USO: 
$db = new Database();
$db->setParametrosBD('ip_host', 'nombre_bd', 'usuario', 'clave');

- SQL simple
$sql = "SELECT * FROM tabla";
$arr_rst = $db->select($sql);
//El resultado es un array asociativo

- SQL Multiple
$sql = "SELECt char_length('esta es una cadena de texto'); SELECT now();";
$arr_multi = $db->multi_query($sql);
//El resultado en un array asociativo

*/
class Database {

private $_dbname, $_dbhost, $_dbuser, $_dbpasswd, $_dblink, $_connection_status;
public $save_queries = false; //Asignar a true si se desea obtener la bitacora de los SQL ejecutados
public $arr_queries = array();

function __construct() {
}

function __destruct() {
$this->close();
}

/**
* Metodo que setea los datos de conexion a la BD
* @access public
* @param string $dbhost Nombre o IP del host de la BD
* @param string $dbname Nombre BD
* @param string $dbuser Usuario de BD
* @param string $dbpasswd Clave usuario BD
*/
public function setBD($dbhost, $dbname, $dbuser, $dbpasswd) {
$this->_dbhost = $dbhost;
$this->_dbname = $dbname;
$this->_dbuser = $dbuser;
$this->_dbpasswd = $dbpasswd;

return $this->connect();
}
/**
* Funcion que retorna un boolean dependiendo si pudo o no conectarse a la BD
* en base a las configuraciones que fueron seteadas en el metodo setParametrosBD
* @access private
* @return boolean
*/
private function connect() {
$conn_string = "host=" . $this->_dbhost . " port=5432 dbname=" . $this->_dbname . " user=" . $this->_dbuser . " password=" . $this->_dbpasswd;

$this->_dblink = pg_connect($conn_string) or die ("Error de conexion. ". pg_last_error());
if ($this->_dblink) {
$this->_connection_status = pg_connection_status($this->_dblink);
return true;
}
return false;
}

/**
* Ejecuta una sentencia SQL
* @access private
* @param string $tSql Cadena SQL a ser ejecutada
* @return resource $res
*/
private function _query($tSql) {
$res = false;
//echo $tSql;

//if (is_resource($this->_dblink) && ( $this->_connection_status === PGSQL_CONNECTION_OK ) ) {
$res = pg_query($this->_dblink, $tSql);
//if($this->save_queries
//$this->arr_queries[] = $tSql;}

return $res;
}
//select
public function select($tSql) {
$result = false;
$res = $this->_query($tSql);

if (!is_bool($res)) {
$result = array();
while ($row = pg_fetch_assoc($res)) {
$result[] = $row;
}
pg_free_result($res);
}

return $result;
}
public function select_list($tSql,$id,$nombre) {

$res = $this->_query($tSql);

while ($row = pg_fetch_assoc($res)) {

echo"<option value=".$row[$id].">".$row[$nombre]."</option>";

}
pg_free_result($res);

}

//retorna el ultimo id ingresado
public function insert($query){

$res = $this->_query($query);
return pg_last_oid($res);

}

public function query($tSql) {
	
$res = $this->_query($tSql);

return $res;
 
}
/*
* Obtener la conexion a la BD
* @access public
* return enlace conexion BD
*/
public function getConexion(){
return $this->_dblink;
}
//funcion cierra

private function close() {
if (!empty($this->_dblink)) {
pg_close($this->_dblink);
unset($this->_dblink);
}
}
}
?>