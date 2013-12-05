<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';

$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');

switch ($task) {	

	default:
		break;
		
	case 'create-company':
		createCompany();
		break;
		
	case 'edit-company':
		editCompany();
		break;
				
	case 'create-fleet':
		createFleet();
		break;
		
	case 'edit-fleet':
		editFleet();
		break;

	case 'create-subfleet':
		createSubfleet();
		break;
		
	case 'create-unit':
		createUnit();
		break;
		
	case 'edit-unit':
		editUnit();
		break;

	case 'move-unit':
		moveUnit();
		break;
		
	case 'delete-company':
		deleteCompany();
		break;
	
}

function createCompany(){
	global $id;
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.css">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.js"></script>
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
		
		$('.datepicker').datepicker({ 
			firstDay: 1,
			autoSize: true,
			dateFormat: "dd-mm-yy" 
		});
		
    });
	</script>
    <section class="create">
    	<form id="form" action="index.code.php">
    		<div id="container">
		        <div class="top">
		        	<h2>Crear compañia</h2>
		        </div>
		        <div class="content">
                     <table width= "100%">
                         <tr>
                              <td><label for="compania">Nombre de la compañia *</label> </td>
                              <td><input type="text" name="compania" id="compania" />  </td>
                            <td><label for="rut">RUT *</label></td>
                            <td><input type="text" name="rut" id="rut" /></td>
                         </tr>
                         <tr>
                            <td><label for="direccion">Dirección *</label></td>
                            <td><input type="text" name="direccion" id="direccion" /></td>
                            <td><label for="contacto">Contacto</label></td>
                            <td><input type="text" name="contacto" id="contacto" /></td>
                         <tr>
                         </tr>
                            <td><label for="mail">Mail</label></td>
                            <td><input type="text" name="mail" id="mail" /></td>
                            <td><label for="fecha_pago">Fecha de pago *</label></td>
                            <td><input class="datepicker" type="text" name="fecha_pago" id="fecha_pago" /></td>
                        </tr>
                    </table>
                    <button type="submit" form="form">Crear Comapñia</button>
		      </div>
		</div>
            <input type="hidden" name="task" value="save-company" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
       
    </section>
    
    <?php
	
}

function editCompany(){
	global $db, $id;

	$sql = $db->query("SELECT * FROM vigia.compania WHERE id_compania=$id");
	$r = pg_fetch_object($sql);
	
	?>
	<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.css">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.js"></script>
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
		
		$('.datepicker').datepicker({ 
			firstDay: 1,
			autoSize: true,
			dateFormat: "dd-mm-yy" 
		});
		
    });
	</script>
    <section class="create">
    	<form id="form" action="index.code.php">
    		<div id="container">
		        <div class="top">
		        	<h2>Editar compañia</h2>
		        </div>
		        <div class="content">
		        	<table width= "100%">
			           <tr>
                            <td><label for="compania">Nombre de la compañia</label> </td>
                            <td><input type="text" name="compania" id="compania" value="<?=$r->compania?>" />  </td>
							<td><label for="rut">RUT</label></td>
							<td><input type="text" name="rut" id="rut" value="<?=$r->rut?>" /></td>
                       </tr>
                       <tr>
							<td><label for="direccion">Dirección</label></td>
							<td><input type="text" name="direccion" id="direccion" value="<?=$r->direccion?>" /></td>
							<td><label for="contacto">Contacto</label></td>
							<td><input type="text" name="contacto" id="contacto" value="<?=$r->contacto?>" /></td>
                       <tr>
                       </tr>
							<td><label for="mail">Mail</label></td>
							<td><input type="text" name="mail" id="mail" value="<?=$r->mail?>" /></td>
							<td><label for="fecha_pago">Fecha de pago</label></td>
							<td><input class="datepicker" type="text" name="fecha_pago" id="fecha_pago" value="<?=formatDate($r->fecha_pago)?>" /></td>
						</tr>
					</table>
					 <button type="submit" form="form">Actualizar datos</button>
		        </div>
			</div>
            <input type="hidden" name="task" value="update-company" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
    </section>
    
    <?php
	
}

function deleteCompany(){
	global $id;
	?>
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript">
    $(function(){
		$("form").submit(function(){
			var form = $(this);
			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
				data: $(this).serialize(),
				success: function(data){
					var response = $.parseJSON(data);
					if(response.type == 'error'){
						alert(response.text);
					}else{
						//alert(response.text);
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
    	<h4>¿Esta seguro que desea eliminar esta compañia?</h4>
        
    	<form id="form" action="index.code.php">
        	<button>Si</button>
        	<button>No</button>
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
        <small>Esta accion no puede revertirse</small>
    </section>
    
	<?php
}

function createFleet(){
	global $id;
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
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
		        	<h2>Crear flota</h2>
		        </div>
		        <div class="content">
		        	<table width= "100%">
			           <tr>
                            <td><label for="flota">Nombre de la flota</label> </td>
                            <td><input type="text" name="flota" id="flota" /> </td>
                            <td><label for="contacto">Contacto</label></td>
                            <td><input type="text" name="contacto" id="contacto" /></td>
						</tr>
						<tr>
							<td><label for="fono1">Teléfono 1</label></td>
                            <td><input type="text" name="fono1" id="fono1" /></td>
							<td><label for="fono2">Teléfono 2</label></td>
                            <td><input type="text" name="fono2" id="fono2" /></td>
						</tr>
						<tr>
							<td><label for="direccion">Dirección</label></td>
                            <td><input type="text" name="direccion" id="direccion" /></td>
							<td><label for="descripcion">Descripción</label></td>
                            <td><textarea name="descripcion" id="descripcion"></textarea></td>
						</tr>
					</table>
					<button type="submit" form="form">Crear flota</button>
		        </div>
		         
		   </div> 
            
            <input type="hidden" name="task" value="save-fleet" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
       
    </section>
    
    <?php
	
}

function editFleet(){
	global $db, $id;
	
	$sql = $db->query("SELECT * FROM vigia.flota WHERE id_flota=$id");
	$r = pg_fetch_object($sql);
	
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
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
		        	<h2>Editar <? if($r->padre != 0) {echo "Sub-";}?>Flota</h2>
		        </div>
		        <div class="content">
		        	<table width= "100%">
			           <tr>
							<td><label for="flota">Nombre de la flota</label> </td>
                            <td><input type="text" name="flota" id="flota" value="<?=$r->nombre_flota?>" /> </td>
							<td><label for="contacto">Contacto</label></td>
                            <td><input type="text" name="contacto" id="contacto" value="<?=$r->contacto?>" /></td>
						</tr>
						<tr>
							<td><label for="fono1">Teléfono 1</label></td>
                            <td><input type="text" name="fono1" id="fono1" value="<?=$r->fono1?>" /></td>
							<td><label for="fono2">Teléfono 2</label></td>
                            <td><input type="text" name="fono2" id="fono2" value="<?=$r->fono2?>" /></td>
						</tr>
						<tr>
							<td><label for="direccion">Dirección</label></td>
                            <td><input type="text" name="direccion" id="direccion" value="<?=$r->direccion?>" /></td>
							<td><label for="descripcion">Descripción</label></td>
                            <td><textarea name="descripcion" id="descripcion"><?=$r->descripcion?></textarea></td>
            			</tr>
          <?
          if($r->padre != 0):
            echo "
            <input type='hidden' name=\"padre_o\" value=\"".$r->padre."\" />
            <tr>
              <td></td><td></td>
              <td><label for=\"padre\">Mover a Flota</label></td>
              <td>
                  <select class=\"subfleet\" name=\"padre\" id=\"padre\"><option >Seleccione Flota</option>";
            $sql2 = $db->query("SELECT * FROM vigia.flota WHERE compania_id_compania=".$r->compania_id_compania."AND padre=0" );
            while ($r2 = pg_fetch_object($sql2)):
             if ($r2->id_flota != $r->padre): 
              echo "<OPTION VALUE='".$r2->id_flota."'>".$r2->nombre_flota." </OPTION>";
              endif;
            endwhile;
            echo "      
              </td>
            </tr>";
          endif;
          ?>
					</table>
					<button type="submit" form="form">Actualizar datos</button>
		        </div>
		         
		   </div> 
            
            <input type="hidden" name="task" value="update-fleet" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
       
    </section>
    
    <?php
	
}

function createSubfleet(){
	global $id;
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
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
		        	<h2>Crear subflota</h2>
		        </div>
		        <div class="content">
		        	<table width= "100%">
			           <tr>
							<td><label for="subfleet">Nombre *</label> </td>
							<td><input type="text" name="subfleet" id="subfleet" />  </td><td><label for="contacto">Número de contacto</label></td>
							<td><input type="text" name="contacto" id="contacto" />  </td>
                        </tr>
                        <tr>
                            <td><label for="descripcion">Descripción :</label></td>
                            <td colspan="3" rowspan="5"><textarea class="textarea-big" name="descripcion" id="descripcion" cols="80" rows="5"></textarea></td>
                        </tr>
					</table>
					 <button type="submit" form="form">Crear subflota</button>
		        </div>
			</div>
            <input type="hidden" name="task" value="save-subfleet" />
            <input type="hidden" name="id" value="<?=$id?>" />
        </form>
       
    </section>
    
    <?php
	
}

function createUnit(){
	global $db, $id;
	
	$sql = $db->query("SELECT * FROM vigia.marcadores");
	
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.css">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.js">
</script>
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/uploadify/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript">
    $(function(){
		$('body').css({'background':'white', 'height':'500px'});
		
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
		
		$('.datepicker').datepicker({ 
			firstDay: 1,
			autoSize: true,
			dateFormat: "dd-mm-yy" 
		});
		
    });
	</script>
    <section class="form">
    	<form id="form" action="index.code.php">
		<div id="container">
            <div class="top">
                <h2>Crear unidad</h2>
            </div>
            <div class="content">
                <table width= "100%">
                <tr>
                    <td> <label for="name" >Nombre :*</label></td>
                    <td><input type="text" name="nombre" id="nombre" /> </td>
                    <td><label for="name" >Imei :*</label></td>
                    <td><input type="text" name="imei" id="imei" /> </td>
                </tr>
                <tr>
                    <td><label for="conductor">Conductor :*</label></td>
                    <td><input type="text" name="conductor" id="conductor" /></td>
                    <td><label for="patente">Patente :*</label></td>
                    <td><input type="text" name="patente" id="patente" /> </td>
                </tr>
                <tr>
                    <td><label for="ano">Año :</label></td>
                    <td><input type="text" name="ano" id="ano" /> </td>
                    <td><label for="marca">Marca :</label></td>
                    <td><input type="text" name="marca" id="marca" /> </td>
                </tr>
                <tr>
                    <td><label for="fecha_mantencion">Mantención :</label></td>
                    <td><input class="datepicker" type="text" name="fecha_mantencion" id="fecha_mantencion" /></td>
                    <td><label for="sensor1">Sensor 1 :</label></td>
                    <td><input type="text" name="sensor1" id="sensor1" /></td>
                </tr>
                <tr>
                    <td><label for="sensor2">Sensor 2 :</label></td>
                    <td><input type="text" name="sensor2" id="sensor2" /></td>
                    <td><label for="sensor3">Sensor 3 :</label></td>
                    <td><input type="text" name="sensor3" id="sensor3" /></td>
                </tr>
                <tr>
                    <td><label for="sensor4">Sensor 4 :</label></td>
                    <td><input type="text" name="sensor4" id="sensor4" /></td>
                    <td> <label for="tipo_gps">Tipo de GPS :*</label></td>
                    <td><input type="text" name="tipo_gps" id="tipo_gps" /></td>
                </tr>
                <tr>
                    <td width="70px" ><label for="numero_sim">Número SIM :*</label></td>
                    <td><input type="text" name="numero_sim" id="numero_sim" /> </td>
                    <td><label for="numero_fono">Número telefono :*</label></td>
                    <td><input type="text" name="numero_fono" id="numero_fono" /></td>
                </tr>
                <tr>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td><label for="id_marcador">Icono:</label></td>
                    <td><select name="id_marcador">
                        <?php while($d = pg_fetch_object($sql)): ?>
                            <option value="<?=$d->id_marcadores?>"><?=$d->descripcion?></option>
                        <?php endwhile; ?>
                        </select>
                    </td>
                    <td><label for="codigo">Codigo*</label></td>
                    <td><input type="text" id="codigo" name="codigo" /></td>
                </tr>
                <tr>
                    <td><label for="descripcion">Descripción :</label></td>
                    <td colspan="3" rowspan="5" ><textarea class="textarea-big" name="descripcion" id="descripcion" cols="80" rows="5"></textarea></td>
                </tr>
                </table>
            
                <input type="hidden" name="task" value="save-unit" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <button type="submit" form="form">Crear unidad</button>		
        	</div>
		</div>
     </form>
    </section>

    <?php
}

function editUnit(){
	global $db, $id;
		
	$sql = $db->query("SELECT * FROM vigia.unidad WHERE id_unidad=".$id);
	$r = pg_fetch_object($sql);
	
	$sql2 = $db->query("SELECT id_marcadores, descripcion FROM vigia.marcadores ORDER BY descripcion");
	
	$sql3 = $db->query("SELECT nombre FROM vigia.usuario WHERE id_usuario=".$_SESSION["userid"]);
	$u = pg_fetch_object($sql3);
	
	$sql4 = $db->query("SELECT * FROM vigia.conf_mapa WHERE id_unidad=$id");
	$cm = pg_fetch_object($sql4);
	
	$sql5 = $db->query("SELECT * FROM vigia.conf_panel WHERE id_unidad=$id");
	$cp = pg_fetch_object($sql5);
	
	$sql6 = $db->query("SELECT * FROM vigia.automoviles");
	
	$sql7 = $db->query("SELECT * FROM vigia.tableros");

	$sql8 = $db->query("SELECT imagen FROM vigia.foto WHERE id_unidad=".$id);
	$f = pg_fetch_object($sql8);
		
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css">
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/uploadify/uploadify.css">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.js">
</script>
	<script type="text/javascript" charset="utf-8">
      var sessionId = "<?=getXss($_SESSION, 'sid')?>";
    </script>
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/uploadify/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript">
    $(function(){
		$('body').css({'background':'white', 'height':'450px'});
		$('#unit').css('display', 'block');
		
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
						//parent.location.reload();
					}
					form.find('button[type=submit]').removeAttr('disabled');
				}
			});
			return false;
		});
		
		$('ul#tabs li a').click(function(){
			var tab = $(this).attr('href');
			$('ul#tabs li a').removeClass('current');
			$(this).addClass('current');
			$('.tab').hide();
			$(tab).fadeIn('slow');
			return false;
		});
		
		$('.datepicker').datepicker({ 
			firstDay: 1,
			autoSize: true,
			dateFormat: "dd-mm-yy" 
		});
		
		$('#file_upload').uploadify({
			'method'   		: 'post',
			'multi'    		: false,
			'swf' 	     	: '<?=SITE_PATH?>/include/scripts/js/uploadify/uploadify.swf',
			'uploader' 		: '<?=SITE_PATH?>/include/scripts/php/uploads.php',
			'fileSizeLimit' : '<?=ini_get('upload_max_filesize')?>B',
			'formData'  	: {'sid':'<?=getXss($_SESSION, 'sid')?>', 'id':'<?=getXss($_REQUEST, 'id')?>', 'task':'image' },
			'buttonClass' 	: 'button btn-swf',
			'height'		: 40,
			'width'			: 140,
			'buttonText' 	: 'Cambiar',
			'fileTypeExts'  : '*.jpg; *.jpeg;',
			'fileTypeDesc'  : 'JPG(.jpg), JPEG(.jpeg)',
			'onSelectError' : function() {
				alert('El Archivo' + file.name + ' no pudo ser subido debido a que existe un limite de tamaño de archivo de <?=ini_get('upload_max_filesize')?>B.');
			},
			'onUploadComplete' : function(file) {
            	//alert('The file ' + file.name + ' finished processing.');
				$.getJSON('index.code.php?task=get-image&id=<?=$id?>', function(data){
					$('#unit-image').attr('src', '../pictures/images_320/'+data.image);
				});
        	}  			
		});
		
		$('#erase').click(function(){
			$.getJSON('index.code.php?task=erase-image&id=<?=$id?>', function(data){
				$('#unit-image').attr('src', '../images/no-picture.jpg');
			});
		});
		
		$('select#id_marcador').change(function () {
			var selected = '';
			$('select#id_marcador option:selected').each(function () {
				selected = $(this).text();
			});
			//$("div").text(str);
			switch (selected){
				case 'Automovil': $('#marcador').html('<img src="../images/icons/active/icon_auto.png" />');break
				case 'Avion': $('#marcador').html('<img src="../images/icons/active/icon_avion.png" />');break
				case 'Barco': $('#marcador').html('<img src="../images/icons/active/icon_bote.png" />');break
				case 'Bus': $('#marcador').html('<img src="../images/icons/active/icon_bus.png" />');break
				case 'Camion': $('#marcador').html('<img src="../images/icons/active/icon_camion.png" />');break
				case 'Estacion': $('#marcador').html('<img src="../images/icons/active/icon_fuente.png" />');break
				case 'Generador': $('#marcador').html('<img src="../images/icons/active/icon_generador.png" />');break
				case 'Grua': $('#marcador').html('<img src="../images/icons/active/icon_grua.png" />');break
				case 'Locomotora': $('#marcador').html('<img src="../images/icons/active/icon_locomotora.png" />');break
				case 'Motocicleta': $('#marcador').html('<img src="../images/icons/active/icon_moto.png" />');break
				case 'Surtidor': $('#marcador').html('<img src="../images/icons/active/icon_tanque.png" />');break
				case 'Tractor': $('#marcador').html('<img src="../images/icons/active/icon_tractor.png" />');break
			}
		})
		.trigger('change');		
    });
	</script>

  <style type="text/css">

section#editunit{
  width: 100%;
  height: 60px;
  margin-top: 12px;
  text-align: center;
}

section#editunit label{
  color: black;
  text-decoration: none;
  font: normal normal 14px 'Open Sans', sans-serif;
  padding-left: 12px;
}

section#editunit input[type=text], textarea{
background-color: #fff;
border: solid 1px #7c7c7c;
margin-top: 11px;
margin-left: 9px;
color: rgb(97, 96, 96);
height: 34px;
width: 208px;
font-size: 14px;
padding-left: 10px;
-webkit-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
-moz-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
font-family: 'open_sansregular';
}

section#editunit input:focus {
    border:1px solid #2a96e2;
    -webkit-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    -moz-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    box-shadow: 0 0 5px rgba(42, 150, 226, 1);
    outline: none;
}

section#editunit textarea{
background-color: #fff;
border: solid 1px #7c7c7c;
margin-top: 11px;
margin-left: 9px;
color: rgb(97, 96, 96);
height: 34px;
width: 208px;
font-size: 14px;
padding-top: 8px;
padding-left: 10px;
-webkit-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
-moz-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
font-family: 'open_sansregular';
}


section#editunit select {
background: transparent;
width: 200px;
padding: 5px;
font-size: 16px;
border: 1px solid #ccc;
height: 36px;
margin-top: 8px;
margin-left: 7px;
font-family: 'open_sansregular';
}

section#editunit select:focus {
    border:1px solid #2a96e2;
    -webkit-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    -moz-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    box-shadow: 0 0 5px rgba(42, 150, 226, 1);
    outline: none;
}


section#editunit button {
  height: 42px;
  width: 153px;
  background: -moz-linear-gradient(
    top,
    #22aae4 0%,
    #229fd1);
  background: -webkit-gradient(
    linear, left top, left bottom, 
    from(#22aae4),
    to(#229fd1));
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  border: 1px solid #2687ad;
  -moz-box-shadow:
    0px 1px 3px rgba(0,0,0,0.2),
    inset 0px 2px 10px rgba(0,0,0,0);
  -webkit-box-shadow:
    0px 1px 3px rgba(0,0,0,0.2),
    inset 0px 2px 10px rgba(0,0,0,0);
  box-shadow:
    0px 1px 3px rgba(0,0,0,0.2),
    inset 0px 2px 10px rgba(0,0,0,0);
  color: #FFF;
  margin: 19px 126px;
  font: normal normal 14px 'Open Sans', sans-serif;
}
section#editunit button:hover {
  background: -moz-linear-gradient(
    top,
    #36b8eb 0%,
    #38afde);
  background: -webkit-gradient(
    linear, left top, left bottom, 
    from(#36b8eb),
    to(#38afde));
  border: 1px solid #2687ad;
  cursor: pointer;
}
section#editunit button:active {
  background: -moz-linear-gradient(
    top,
    #22aae4 0%,
    #229fd1);
  background: -webkit-gradient(
    linear, left top, left bottom, 
    from(#22aae4),
    to(#229fd1));
  -moz-box-shadow:
    0px 0px 0px rgba(0,0,0,0.0),
    inset 0px 1px 3px rgba(0,0,0,0.2);
  -webkit-box-shadow:
    0px 0px 0px rgba(0,0,0,0.0),
    inset 0px 1px 3px rgba(0,0,0,0.2);
  box-shadow:
    0px 0px 0px rgba(0,0,0,0.0),
    inset 0px 1px 3px rgba(0,0,0,0.2);
}
section#editunit hr {
    border: 0;
    height: 1px;
    background-image: -webkit-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:    -moz-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:     -ms-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:      -o-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
}
section#editunit h6 {
    text-align: center;
    font: normal normal 15px 'Open Sans', sans-serif;
    color: #22aae4;
}

section#editunit div#maintenance input[type=text], textarea{
background-color: #fff;
border: solid 1px #7c7c7c;
margin-top: 11px;
margin-left: 9px;
color: rgb(97, 96, 96);
height: 34px;
width: 208px;
font-size: 14px;
padding-left: 10px;
-webkit-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
-moz-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
font-family: 'open_sansregular';
}

section#editunit div#maintenance input:focus {
    border:1px solid #2a96e2;
    -webkit-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    -moz-box-shadow:0 1px 2px rgba(42, 150, 226, 1);
    box-shadow: 0 0 5px rgba(42, 150, 226, 1);
    outline: none;
}

section#editunit div#maintenance textarea{
background-color: #fff;
border: solid 1px #7c7c7c;
margin-top: 11px;
margin-left: 9px;
color: rgb(97, 96, 96);
height: 34px;
width: 208px;
font-size: 14px;
padding-top: 8px;
padding-left: 10px;
-webkit-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
-moz-box-shadow: 0 1px 2px rgba(0,0, 0, 0.1) inset;
font-family: 'open_sansregular';
}

#id_upload{
	position: absolute;
    width: 140px;
}
#erase{
	position:absolute;
	right: 17px;
    top: 297px;
}
.swfupload{
	left:137px;
	top:10px;
}

  </style>
    	
    <section class="edit-unit" id="editunit">
        <form id="form" action="index.code.php">
        	<ul id="tabs">
            	<li><a class="current" href="#unit">Unidad</a></li>
                <li><a href="#hardware">Hardware</a></li>
                <li><a href="#settings">Configuración</a></li>
                <li><a href="#ios">IOs</a></li>
                <li><a href="#picture">Imagen</a></li>
                <li><a href="#notification">Notificaciones</a></li>
                <li><a href="#maintenance">Mantenimiento</a></li>
                <li><a href="#events">Eventos</a></li>
            </ul>
            <div class="clear20"></div>
            
            <!-- INICIO: Pestaña de UNIDAD -->
            <div class="tab" id="unit">

              <table width= "100%">
                  <tr>
                      <td width="33%"> <label for="id_unidad">Id unidad</label> </td>
                      <td width="33%"> <input disabled="disabled" type="text" id="id_unidad" value="<?=$id?>" /></td>
                      <td width="33%"></td>
                  </tr>
                  <tr>
                      <td> <label for="fecha_reg">Fecha activación</label> </td>
                      <td> <input disabled="disabled" type="text" id="fecha_reg" name="fecha_reg" value="<?=formatDate($r->fecha_reg)?>" /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="nombre">Nombre</label> </td>
                      <td> <input type="text" id="nombre" name="nombre" value="<?=$r->nombre?>" /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="descripcion">Descripción</label> </td>
                      <td> <input type="text" id="descripcion" name="descripcion" value="<?=$r->descripcion?>" /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="observaciones">Observaciones</label> </td>
                      <td> <input type="text" id="observaciones" name="observaciones" value="<?=$r->observaciones?>" /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="unidad_est">Unidad estacionaria</label> </td>
                      <td> <input type="checkbox" class="regular-checkbox" id="unidad_est" name="unidad_est" <?php if($r->unidad_est == 1) echo 'checked="checked"'; ?> /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="id_marcador">Icono:</label> </td>
                      <td width="100px"> <select id="id_marcador" name="id_marcador">
                              <?php while($m = pg_fetch_object($sql2)): ?>
                              <option <? if($m->id_marcadores == $r->id_marcadores) echo 'selected="selected"';?> value="<?=$m->id_marcadores?>"><?=$m->descripcion?></option>
                                <?php endwhile; ?>
                                </option>
                            </select>
                      </td>
                      <td id="marcador"></td>
                  </tr>
                  <tr>
                      <td> <label for="unidad_diag">Unidad de diagnostico</label> </td>
                      <td> <input type="checkbox" class="regular-checkbox" id="unidad_diag" name="unidad_diag" <?php if($r->unidad_diag == 1) echo 'checked="checked"'; ?> /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="actualizacion">Última actualización</label> </td>
                      <td> <input type="text" disabled="disabled" id="actualizacion" name="actualizacion" value="<?=formatDate($r->actualizacion)?>" /> </td>
                      <td></td>
                  </tr>
                  <tr>
                      <td> <label for="usuario">Por usuario</label> </td>
                      <td> <input type="text" disabled="disabled" id="usuario" name="usuario" value="<?=$u->nombre?>" /> </td>
                      <td></td>
                  </tr>
                  
              </table>
            </div>
            <!-- FIN: Pestaña de UNIDAD -->
            
            <!-- INICIO: Pestaña de HARDWARE -->
            <div class="tab" id="hardware">
              <table width= "100%">
                  <tr>
                      <td> <label for="id_unidad">Id unidad</label> </td><td> <input disabled="disabled" type="text" id="id_unidad" value="<?=$id?>" /> </td>
                   </tr>
                   <tr>
                      <td> <label for="numero_fono">SIM número telefono</label> </td><td> <input type="text" id="numero_fono" name="numero_fono" value="<?=$r->numero_fono?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="numero_sim">SIM numero de serie</label> </td><td> <input type="text" id="numero_sim" name="numero_sim" value="<?=$r->numero_sim?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="modem_imei">Modem IMEI</label> </td><td> <input type="text" id="modem_imei" name="modem_imei" value="<?=$r->modem_imei?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="plate">Plato/Numero de serie</label> </td><td> <input type="text" id="plate" name="plate" value="<?=$r->plate?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="marca">Marca y modelo</label> </td>
                      <td> 
                        <select id="marca" name="marca">
                        <?php while($a = pg_fetch_object($sql6)): ?>
                        	<option <?php if($r->id_marca == $a->id_automoviles) echo 'selected="selected"'; ?> value="<?=$a->id_automoviles?>"><?=$a->nombre?></option>
                        <?php endwhile; ?>
                        </select>
                      </td>
                  </tr>
                  <tr>
                      <td> <label for="tipo_gps">Tipo de unidad</label> </td><td> <input type="text" id="tipo_gps" name="tipo_gps" value="<?=$r->tipo_gps?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="version_unidad">Version de unidad</label> </td><td> <input type="text" id="version_unidad" name="version_unidad" value="<?=$r->version_unidad?>" /> </td>
                  </tr>
              </table>
            </div>
            <!-- FIN: Pestaña de HARDWARE -->
            
            <!-- INICIO: Pestaña de CONFIGUARCION -->
            <div class="tab" id="settings">
              <table width= "100%">
                  <tr>
                      <td> <label for="idle">Tiempo de ralentí</label> </td>
                      <td> <input type="text" id="idle" name="idle" value="<?php if(!$r->idle) echo '00:00:00'; else echo $r->idle; ?>" /> </td>
                      <td> <label for="distancia">Distancia</label> </td>
                      <td> <input type="text" id="distancia" name="distancia" value="<?=$r->distancia?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="externo">Dispositivo externo</label> </td>
                      <td>  
                          <select id="externo" name="externo">
                            <option>seleccionar</option>
                              <option <?php if($r->externo == 'MDT') echo 'selected="selected"'; ?> value="MDT">MDT</option>
                              <option <?php if($r->externo == 'Mobileeye') echo 'selected="selected"'; ?> value="Mobileeye">Mobileeye</option>
                              <option <?php if($r->externo == 'Netvim') echo 'selected="selected"'; ?> value="Netvim">Netvim</option>
                          </select>
                      </td>
                      <td> <label for="motor">Horas de motor</label> </td>
                      <td> <input type="text" id="motor" name="motor" value="<?=$r->motor?>" /> </td>
                   </tr>
                  <tr>
                      <td> <label for="tablero">Tablero</label> </td>
                      <td>
                        <select id="tablero" name="tablero">
                        <?php while($t = pg_fetch_object($sql7)): ?>
                        	<option <?php if($r->id_tableros == $t->id_tableros) echo 'selected="selected"'; ?> value="<?=$t->id_tableros?>"><?=$t->nombre?></option>
                        <?php endwhile; ?>
                        </select>
                      </td>
                      <td>  </td><td>  </td>
                  </tr>
              </table>
            	
            	 <hr/>
                    
                    <table width= "100%">
                        <tr>
                            <td> <h6>Estado</h6> </td>
                            <td> <h6>Mapa</h6> </td>
                            <td> <h6>Panel</h6> </td>
                        </tr>
                        <tr>
                            <td> <label for="tiempo">Tiempo</label> </td>
                            <td align="center"> <input <?php if($cm->m_tiempo==1) echo 'checked="checked"' ?> type="checkbox" id="tiempo" name="m_tiempo" /> </td>
                            <td align="center"> <input <?php if($cp->p_tiempo==1) echo 'checked="checked"' ?> type="checkbox" id="tiempo" name="p_tiempo" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="direccion">Dirección</label> </td>
                            <td align="center"> <input <?php if($cm->m_direccion==1) echo 'checked="checked"' ?> type="checkbox" id="direccion" name="m_direccion" /> </td>
                            <td align="center"> <input <?php if($cp->p_direccion==1) echo 'checked="checked"' ?> type="checkbox" id="direccion" name="p_direccion" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="estado">Estado</label> </td>
                            <td align="center"> <input <?php if($cm->m_estado==1) echo 'checked="checked"' ?> type="checkbox" id="estado" name="m_estado" /> </td> 
                            <td align="center"> <input <?php if($cp->p_estado==1) echo 'checked="checked"' ?> type="checkbox" id="estado" name="p_estado" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="velocidad">Velocidad</label> </td>
                            <td align="center"> <input <?php if($cm->m_velocidad==1) echo 'checked="checked"' ?> type="checkbox" id="velocidad" name="m_velocidad" /> </td>
                            <td align="center"> <input <?php if($cp->p_velocidad==1) echo 'checked="checked"' ?> type="checkbox" id="velocidad" name="p_velocidad" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="grado">Grado</label> </td>
                            <td align="center"> <input <?php if($cm->m_grado==1) echo 'checked="checked"' ?> type="checkbox" id="grado" name="m_grado" /> </td>
                            <td align="center"> <input <?php if($cp->p_grado==1) echo 'checked="checked"' ?> type="checkbox" id="grado" name="p_grado" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="distancia">distancia</label> </td>
                            <td align="center"> <input <?php if($cm->m_distancia==1) echo 'checked="checked"' ?> type="checkbox" id="distancia" name="m_distancia" /> </td>
                            <td align="center"> <input <?php if($cp->p_distancia==1) echo 'checked="checked"' ?> type="checkbox" id="distancia" name="p_distancia" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="horas_motor">Horas de motor</label> </td>
                            <td align="center"> <input <?php if($cm->m_horas_motor==1) echo 'checked="checked"' ?> type="checkbox" id="horas_motor" name="m_horas_motor" /> </td>
                            <td align="center"> <input <?php if($cp->p_horas_motor==1) echo 'checked="checked"' ?> type="checkbox" id="horas_motor" name="p_horas_motor" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="v_externo">Voltaje externo</label> </td>
                            <td align="center"> <input <?php if($cm->m_volt_externo==1) echo 'checked="checked"' ?> type="checkbox" id="volt_externo" name="m_volt_externo" /> </td>
                            <td align="center"> <input <?php if($cp->p_volt_externo==1) echo 'checked="checked"' ?> type="checkbox" id="externo" name="p_volt_externo" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="v_respaldo">Voltaje de respaldo</label> </td>
                            <td align="center"> <input <?php if($cm->m_volt_respaldo==1) echo 'checked="checked"' ?> type="checkbox" id="m_volt_respaldo" name="m_volt_respaldo" /> </td>
                            <td align="center"> <input <?php if($cp->p_volt_respaldo==1) echo 'checked="checked"' ?> type="checkbox" id="v_respaldo" name="p_volt_respaldo" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="alarma">Alarma</label> </td>
                            <td align="center"> <input <?php if($cm->m_alarma==1) echo 'checked="checked"' ?> type="checkbox" id="alarma" name="m_alarma" /> </td>
                            <td align="center"> <input <?php if($cp->p_alarma==1) echo 'checked="checked"' ?> type="checkbox" id="alarma" name="p_alarma" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="tipo_viaje">Tipo de viaje</label> </td>
                            <td align="center"> <input <?php if($cm->m_tipo_viaje==1) echo 'checked="checked"' ?> type="checkbox" id="tipo_viaje" name="m_tipo_viaje" /> </td>
                            <td align="center"> <input <?php if($cp->p_tipo_viaje==1) echo 'checked="checked"' ?> type="checkbox" id="tipo_viaje" name="p_tipo_viaje" /> </td>
                        </tr>
                    <tr>
                    	<tr>
                            <td colspan="3"> <hr/> </td>
                        </tr>
                            <td> <h6>GPS</h6></td>
                            <td> <h6>Mapa</h6></td>
                            <td> <h6>Panel</h6></td>
                        </tr>
                        <tr>
                            <td> <label for="tiempo_gps">Tiempo de GPS</label> </td>
                            <td align="center"> <input <?php if($cm->m_tiempo_gps==1) echo 'checked="checked"' ?> type="checkbox" id="tiempo_gps" name="m_tiempo_gps" /> </td>
                            <td align="center"> <input <?php if($cp->p_tiempo_gps==1) echo 'checked="checked"' ?> type="checkbox" id="tiempo_gps" name="p_tiempo_gps" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="satelites">Satelites</label> </td>
                            <td align="center"> <input <?php if($cm->m_satelites==1) echo 'checked="checked"' ?> type="checkbox" id="satelites" name="m_satelites" /> </td>
                            <td align="center"> <input <?php if($cp->p_satelites==1) echo 'checked="checked"' ?> type="checkbox" id="satelites" name="p_satelites" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="hdop">HDOP</label> </td>
                            <td align="center"> <input <?php if($cm->m_hdop==1) echo 'checked="checked"' ?> type="checkbox" id="hdop" name="m_hdop" /> </td>
                            <td align="center"> <input <?php if($cp->p_hdop==1) echo 'checked="checked"' ?> type="checkbox" id="hdop" name="p_hdop" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="latlng">Lat/Lng</label> </td>
                            <td align="center"> <input <?php if($cm->m_latlng==1) echo 'checked="checked"' ?> type="checkbox" id="latlng" name="m_latlng" /> </td>
                            <td align="center"> <input <?php if($cp->p_latlng==1) echo 'checked="checked"' ?> type="checkbox" id="latlng" name="p_latlng" /> </td>
                        </tr>
                        <tr>
                            <td colspan="3"> <hr/> </td>
                        </tr>
                        <tr>
                            <td> <h6>Comunicación</h6></td>
                            <td> <h6>Mapa</h6></td>
                            <td> <h6>Panel</h6></td>
                        </tr>
                        <tr>
                            <td> <label for="gsm">Calidad de señal GSM</label> </td>
                            <td align="center"> <input <?php if($cm->m_gsm==1) echo 'checked="checked"' ?> type="checkbox" id="gsm" name="m_gsm" /> </td>
                            <td align="center"> <input <?php if($cp->p_gsm==1) echo 'checked="checked"' ?> type="checkbox" id="gsm" name="p_gsm" /> </td>
                        </tr>
                        <tr>
                            <td colspan="3"> <hr/> </td>
                        </tr>
                        <tr>
                            <td> <h6>Información</h6></td>
                            <td> <h6>Mapa</h6></td>
                            <td> <h6>Panel</h6></td>
                        </tr>
                        <tr>
                            <td> <label for="obs_unidad">Observaciones de unidad</label> </td>
                            <td align="center"> <input <?php if($cm->m_obs_unidad==1) echo 'checked="checked"' ?> type="checkbox" id="m_obs_unidad" name="m_obs_unidad" /> </td>
                            <td align="center"> <input <?php if($cp->p_obs_unidad==1) echo 'checked="checked"' ?> type="checkbox" id="obs_unidad" name="p_obs_unidad" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="des_unidad">Descripcion de unidad</label> </td>
                            <td align="center"> <input <?php if($cm->m_des_unidad==1) echo 'checked="checked"' ?> type="checkbox" id="des_unidad" name="m_des_unidad" /> </td>
                            <td align="center"> <input <?php if($cp->p_des_unidad==1) echo 'checked="checked"' ?> type="checkbox" id="des_unidad" name="p_des_unidad" /> </td>
                        </tr>
                        <tr>
                            <td> <label for="num_plato">Número de plato</label></td>
                            <td align="center"> <input <?php if($cm->m_num_plato==1) echo 'checked="checked"' ?> type="checkbox" id="num_plato" name="m_num_plato" /> </td>
                            <td align="center"> <input <?php if($cp->p_num_plato==1) echo 'checked="checked"' ?> type="checkbox" id="num_plato" name="p_num_plato" /> </td>
                        </tr>
                    </table>
                     
               
                <div class="clear"></div>
            </div>
            <!-- FIN: Pestaña de CONFIGUARCION -->
            
            <!-- INICIO: Pestaña IOS -->
            <div class="tab" id="ios">
            	Tab de IOS
            </div>
            <!-- FIN: Pestaña IOS -->
            
            <!-- INICIO: Pestaña de PICTURE -->
            <div class="tab" id="picture">
            <?php if(!$f->imagen): ?>
            	<img id="unit-image" src="../images/no-picture.jpg" width="320" height="240" style="box-shadow: 1px 4px 20px -5px #37B2E3;" />
            <?php else: ?>
            	<img id="unit-image" src="../pictures/images_320/<?=$f->imagen?>" width="320" height="240" style="box-shadow: 1px 4px 20px -5px #37B2E3;" />
            <?php endif; ?>
                <div class="wrap">
                	<input type="file" name="file_upload" id="file_upload" />
                	<button id="erase" type="button">Eliminar</button>
                    <div class="clear"></div>
                </div>
            </div>
            <!-- FIN: Pestaña de PICTURE -->
            
            <!-- INICIO: Pestaña de NOTIFICACIONES -->
            <div class="tab" id="notification">
              <table width= "100%">
                  <tr>
                      <td> <label for="email_notificaciones">E-mail(s) de destino<br /><small>(Separados por ",")</small></label> </td><td> <textarea id="email_notificaciones" name="email_notificaciones" style="max-width: 350px; min-width:350px; max-height:150px; min-height:150px;"><?=$r->email_notificaciones?></textarea> </td>
                  </tr>
              </table>
            </div>
            <!-- FIN: Pestaña de NOTIFICACIONES -->
            
            <!-- INICIO: Pestaña de MANTENCIONES -->
            <div class="tab" id="maintenance">
              <table width= "100%">
                  <tr>
                      <td> <label for="licencia">Siguiente licencia</label> </td><td> <input type="text" class="datepicker" id="licencia" name="licencia" value="<?=formatDate($r->licencia)?>" />  </td>
                  </tr>
                  <tr>
                      <td> <label for="mantencion1">Siguiente mantencion</label> </td><td> <input type="text" class="datepicker" id="mantencion1" name="mantencion1" value="<?=formatDate($r->mantencion1)?>" />  </td>
                  </tr>
                  <tr>
                      <td> <label for="mantencion2">Siguiente mantencion 2</label> </td><td>  <input type="text" class="datepicker" id="mantencion2" name="mantencion2" value="<?=formatDate($r->mantencion2)?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="mantencion3">Siguiente mantencion 3</label> </td><td>  <input type="text" class="datepicker" id="mantencion3" name="mantencion3" value="<?=formatDate($r->mantencion3)?>" /> </td>
                  </tr>
                  <tr>
                      <td> <label for="man_distancia">Siguiente mantencion por distancia</label> </td><td> <input type="text" id="man_distancia" name="man_distancia" value="<?=$r->man_distancia?>" />  </td>
                  </tr>
                  <tr>
                      <td> <label for="man_motor">Siguiente mantencion por horas de motor</label> </td><td> <input type="text" id="man_motor" name="man_motor" value="<?=$r->man_motor?>" />  </td>
                  </tr>
                  <tr>
                      <td> <label for="exp_garantia">Expiración de garantia</label> </td><td> <input type="text" class="datepicker" id="exp_garantia" name="exp_garantia" value="<?=formatDate($r->exp_garantia)?>" />  </td>
                  </tr>
                  <tr>
                      <td> <label for="chequeo">Siguiente chequeo</label> </td><td>  <input type="text" class="datepicker" id="chequeo" name="chequeo" value="<?=formatDate($r->chequeo)?>" /> </td>
                  </tr>
              </table>
            </div>
            <!-- FIN: Pestaña de MANTENCIONES -->
            
            <!-- INICIO: Pestaña de EVENTOS -->
            <div class="tab" id="events">
            	Tab de eventos
            </div>
            <!-- FIN: Pestaña de MANTENCIONES -->
            
        <div class="clear20"></div> 
        <input type="hidden" name="task" value="update-unit" />
        <input type="hidden" name="id" value="<?=$id?>" />
        <input type="hidden" name="id_usuario" value="<?=$_SESSION["userid"]?>" />
        <button type="submit" form="form">Guardar cambios</button>		
		
        </form>
        
    </section>


    
    <?php
	
}

function moveUnit(){
  global $db, $id;
  
  $sb = $db->query("SELECT u.id_flota, (SELECT padre FROM vigia.flota WHERE id_flota=u.id_flota AND borrado='FALSE' LIMIT 1) AS padre FROM vigia.unidad AS u WHERE id_unidad=$id");
  $p = pg_fetch_object($sb);
  
if($p->padre == 0):
	$com = $db->query("SELECT compania_id_compania FROM vigia.flota WHERE id_flota=".$p->id_flota." AND borrado=FALSE");
	$c = pg_fetch_object($com);
else:
	$com = $db->query("SELECT compania_id_compania FROM vigia.flota WHERE id_flota=".$p->padre." AND borrado=FALSE");	
	$c = pg_fetch_object($com);
endif;
  
  $sql = $db->query("SELECT * FROM vigia.flota AS f JOIN vigia.compania AS c ON (f.compania_id_compania=c.id_compania) WHERE f.padre=0 AND c.id_compania=f.compania_id_compania AND f.compania_id_compania=".$c->compania_id_compania." AND f.borrado='FALSE'");

  $sql2 = $db->query("SELECT * FROM vigia.flota WHERE padre<>0 AND borrado='FALSE'");
  
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
                <h2>Mover unidad</h2>
            </div>
            <div class="content">
              <form id="form" action="index.code.php">
              <table width= "100%">
              	<tr>
                    <td><label for="flota">Hacia una flota</label></td>
                    <td><select id="flota" name="flota">
                        <option value=""></option>
                      <?php while ($r = pg_fetch_object($sql)):?>
                        <option value="<?=$r->id_flota?>"><?=$r->nombre_flota?></option>
                      <?php endwhile; ?>
                    </select>
                    </td>
                  </tr>
                  <tr> 
                    <td><label for="subflota">Hacia una subflota</label></td>
                    <td><select id="subflota" name="subflota">
                        <option value=""></option>
                      <?php while($d = pg_fetch_object($sql2)): 
					  		$flag = selectFleetCompany($d->id_flota, $c->compania_id_compania);
							if($flag == 'yes'): ?>
                        <option value="<?=$d->id_flota?>"><?=$d->nombre_flota?></option>
                      <?php endif; endwhile; ?>
                    </select>
            		</td>
                  </tr>
                </table>
                    <input type="hidden" name="task" value="move-unit">
                    <input type="hidden" name="id" value="<?=$id?>"> 
              </form>
              <button type="submit" form="form">Guardar cambios</button>
           </div>
        </div>

    </section>

    <?php
}

