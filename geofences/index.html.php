<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';
$envio_geofence="document.getElementById(\"nombre\").value = nombre;
			$.post('index.code.php', {task: $('#task').val(), id: $('#id').val(), nombre: $('#nombre').val(), action: $('#action').val()}, function(data){
				alert(data.type+'\n\n'+data.text);
				parent.location.reload();
			}, \"json\");
		return false;";
$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');

switch ($task) {	

	default:
		break;
		
	case 'create-group':
		createGroup();
		break;
		
	case 'create-geofence':
		createGeofence();
		break;
		
	case 'move-geo':
		moveGeofence();
		break;
		
	case 'edit-group':
		editGroup();
		break;
		
	case 'save-geo':
		$action = getXss($_REQUEST, 'action');
		$nombre = getXss($_REQUEST, 'nombre');
		$descripcion = getXss($_REQUEST, 'descripcion');
		saveGeo($action, $nombre, $descripcion);
		break;
}

function createGroup(){
	global $id;
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfix.js"></script>
	<script type="text/javascript">
    $(function(){
		$('body').css('background-color', 'white');
		
		$("form").submit(function(){
			var form = $(this);
			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
				data: $(this).serialize(),
				success: function(data){
					var response = $.parseJSON(data);
					if(response.type == 'ERROR:'){
						alert(response.type+'\n\n'+response.text);
					}else{
						//alert(response.type+'\n\n'+response.text);
						parent.location.reload();
					}
					form.find('button[type=submit]').removeAttr('disabled');
				}
			});
			return false;
		});
				
    });
	</script>
    <section class="create">
    	<form id="form" action="index.code.php">
    		<div id="container">
		        <div class="top">
		        	<h2>Crear grupo</h2>
		        </div>
		        <div class="content">
                     <table width= "100%">
                         <tr>
                             <td><label for="grupo">Nombre</label> </td>
                             <td><input type="text" name="grupo" id="grupo" />  </td>
                         </tr>
                    </table>
                    <button type="submit" form="form">Crear Grupo</button>
		      </div>
		</div>
            <input type="hidden" name="task" value="save-group" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
       
    </section>
    
    <?php
	
}

function moveGeofence(){
  global $db, $id;
  
  $sql = $db->query("SELECT g.fk_id_grupos, (SELECT id_compania FROM vigia.grupos WHERE id_grupos=g.fk_id_grupos) AS id_compania FROM vigia.geofences AS g WHERE id_geofence=$id");
  $r = pg_fetch_object($sql);
  
  $sql2 = $db->query("SELECT * FROM vigia.grupos WHERE id_compania=".$r->id_compania);
    
  ?>
  <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
  <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript">
    $(function(){
		$('body').css({'background':'white'});
		
      $("form").submit(function(){
        var form = $(this);
        $.ajax({
          url: form.attr('action'),
          type: 'POST',
          beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
          data: $(this).serialize(),
          success: function(data){
            var response = $.parseJSON(data);
            if(response.type == 'ERROR:'){
              alert(response.type+'\n\n'+response.text);
            }else{
              //alert(response.type+'\n\n'+response.text);
              parent.location.reload();
            }
            form.find('button[type=submit]').removeAttr('disabled');
          }
        });
        return false;
      });
    });
    </script>
    <section class="create">
		<div id="container">
            <div class="top">
                <h2>Mover geocerca</h2>
            </div>
            <div class="content">
              <form id="form" action="index.code.php">
              <table width= "100%">
              	<tr>
                    <td><label for="id_grupo">Hacia el grupo</label></td>
                    <td><select id="id_grupo" name="id_grupo">
                        <option value=""></option>
                      <?php while ($d = pg_fetch_object($sql2)):?>
                        <option value="<?=$d->id_grupos?>"><?=$d->grupo?></option>
                      <?php endwhile; ?>
                    </select>
                    </td>
                  </tr>
                </table>
                    <input type="hidden" name="task" value="move-geo">
                    <input type="hidden" name="id" value="<?=$id?>"> 
              </form>
              <button type="submit" form="form">Mover</button>
           </div>
        </div>

    </section>

    <?php
}

function editGroup(){
  global $db, $id;
  
  $sql = $db->query("SELECT * FROM vigia.grupos WHERE id_grupos=$id");
  $r = pg_fetch_object($sql);
  
  ?>
  <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
  <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript">
    $(function(){
		$('body').css({'background':'white'});
		
      $("form").submit(function(){
        var form = $(this);
        $.ajax({
          url: form.attr('action'),
          type: 'POST',
          beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
          data: $(this).serialize(),
          success: function(data){
            var response = $.parseJSON(data);
            if(response.type == 'ERROR:'){
              alert(response.type+'\n\n'+response.text);
            }else{
              //alert(response.type+'\n\n'+response.text);
              parent.location.reload();
            }
            form.find('button[type=submit]').removeAttr('disabled');
          }
        });
        return false;
      });
    });
    </script>
    <section class="create">
		<div id="container">
            <div class="top">
                <h2>Editar Grupo</h2>
            </div>
            <div class="content">
              <form id="form" action="index.code.php">
              <table width= "100%">
              	<tr>
                    <td><label for="grupo">Nombre del grupo</label></td>
                    <td><input type="text" id="grupo" name="grupo" value="<?=$r->grupo?>" /></td>
                  </tr>
                </table>
                    <input type="hidden" name="task" value="edit-group">
                    <input type="hidden" name="id" value="<?=$id?>"> 
              </form>
              <button type="submit" form="form">Guardar</button>
           </div>
        </div>

    </section>

    <?php
}

function saveGeo($action, $nombre, $descripcion){
	global $db;
	
	$type = explode(';',$action);
	
	$sql = $db->query("SELECT id_unidad, nombre FROM vigia.unidad WHERE borrado='false'");
	$sql2 = $db->query("SELECT id_grupos, grupo FROM vigia.grupos");
	$sql3 = $db->query("SELECT id_puntos_interes, descripcion FROM vigia.puntos_interes ORDER BY id_puntos_interes");
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfix.js"></script>
	<script type="text/javascript">
    $(function(){
		$('body').css('background-color', 'white');
		
		$("form").submit(function(){
			var form = $(this);
			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
				data: $(this).serialize(),
				success: function(data){
					var response = $.parseJSON(data);
					if(response.type == 'ERROR:'){
						alert(response.type+'\n\n'+response.text);
					}else{
						//alert(response.type+'\n\n'+response.text);
						parent.location.reload();
					}
					form.find('button[type=submit]').removeAttr('disabled');
				}
			});
			return false;
		});
				
    });
	</script>
    <section class="create">
    	<form id="form" action="index.code.php">
    		<div id="container">
		        <div class="top">
		        	<h2>Guardar geocerca</h2>
		        </div>
		        <div class="content">
		        	<table width= "100%">
			           <tr>
							<td><label for="nombre">Nombre geocerca</label> </td>
							<td><input type="text" name="nombre" id="nombre" />  </td>
                            <td><label for="unidad">Asignar a unidad</label></td>
							<td>  
                            	<select id="unidad" name="unidad">
                                	<option></option>
                                	<?php while($r = pg_fetch_object($sql)): ?>
                                	<option value="<?=$r->id_unidad?>"><?=$r->nombre?></option>
                                    <?php endwhile; ?>
                                </select>
                            </td>
                       </tr>
                   	   <tr>
                            <td><label for="grupo">Agregar a grupo</label></td>
							<td>  
                            	<select id="grupo" name="grupo">
                                	<option></option>
                                	<?php while($d = pg_fetch_object($sql2)): ?>
                                	<option value="<?=$d->id_grupos?>"><?=$d->grupo?></option>
                                    <?php endwhile; ?>
                                </select>
                            </td>
                            <?php if($type[0] == 'Marker'): ?>
                            <td><label for="marcador">Marcador</label></td>
							<td>  
                            	<select id="marcador" name="marcador">
                                	<?php while($f = pg_fetch_object($sql3)): ?>
                                	<option value="<?=$f->id_puntos_interes?>"><?=$f->descripcion?></option>
                                    <?php endwhile; ?>
                                </select>
                            </td>
                            <?php else: ?>
                            	<input hidden="hidden" name="marcador" value="1" />
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción :</label></td>
                            <td colspan="3" rowspan="5"><textarea class="textarea-big" name="descripcion" id="descripcion" cols="80" rows="5"></textarea></td>
                        </tr>
					</table>
					 <button type="submit" form="form">Guardar</button>
		        </div>
			</div>
            <input type="hidden" name="task" value="save-geofence" />
            <input type="hidden" name="action" value="<?=$action?>" />
        </form>
       
    </section>
    
    <?php
	
}

