<?
include "config.php";

//print_r($usr);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>flotas-form</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="insert_flota.php">
  <table width="360" border="0">
    <tr>
      <td width="206"><label>NOMBRE: </label></td>
      <td width="144"><input name="nombre" type="text" id="nombre" value="" /></td>
    </tr>
    
    <tr>
      <td>DESCRIPCIÓN: </td>
      <td><input name="descripcion" type="text" id="telefono" value=""/></td>
    </tr>
    <tr>
      <td>CONTACTO:</td>
      <td><input name="email" type="text" id="email" value=""/></td>
    </tr>
    <tr>
      <td>FLOTA PADRE: </td>
      <td>
        <select name="padre">
          <option value="0"> Sin Padre </option>
          <? $db->select_list("SELECT * FROM vigia.flota WHERE padre='0'","id_flota","nombre_flota"); ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>COMPA&Ntilde;IA:</td>
      <td><label>
        <select name="compania">
		<? $db->select_list("SELECT * FROM vigia.compania","id_compania","compania"); ?>
		</select>
      </label></td>
    </tr>

    <tr>
      <td colspan="2"><label>
        <div align="center">
          <input type="submit" name="Submit" value="Guardar" />
          </div>
      </label></td>
    </tr>
  </table>

</form>
</body>
</html>
