<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';

$task = getXss($_REQUEST, 'task');
$id = getXss($_REQUEST, 'id');


switch ($task) {
		
	case 'save-user':
		saveUser();
		break;
		
	case 'edit-user':
		editUser();
		break;
	
	case 'edit-pass-user':
		editPassUser();
		break;	
	
	case 'save-asign-fleet':
        $companias = getXss($_REQUEST, 'companias');
		saveAsignFleet($companias);
		break;
		
	case 'profile':
		profile();
		break;
		
	case 'edit-profile';
		editProfile();
		break;
}

function saveUser(){
	global $db;
?>	
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <!--<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfx.min.js"></script>-->
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
					if(response.type == 'ERROR:'){
						alert(response.type+'\n\n'+response.text);
					}else if(response.type == 'OK:'){
						location='index.html.php?task=save-asign-fleet&id='+response.id+'&companias='+response.companias;
					}
					form.find('button[type=submit]').removeAttr('disabled');
                }
            });
            return false;
        });	
        
    });
    
    </script>
    </head>
    <body class="white">
    <section class="users-new">
        <h3>Agregar usuario</h3>
        <div class="steps">
            <div class="step-1"><h6>Paso 1:</h6><small>Ingrese los datos del usuario</small></div>
            <div class="step-2"><h6>Paso 2:</h6><small>Asigne las flotas correspondientes</small></div>
            <div class="clear"></div>
        </div>
        <form id="form1" action="ingreso.php">
          <table border="0">
            <tr>
              <td><label>Nombre *</label></td>
              <td><input name="nombre" type="text" id="nombre" /></td>
            </tr>
            
            <tr>
              <td><label>Teléfono</label></td>
              <td><input name="telefono" type="text" id="telefono" /></td>
            </tr>
            <tr>
              <td><label>E-mail</label></td>
              <td><input name="email" type="text" id="email" /></td>
            </tr>
            <tr>
              <td><label>Nombre usuario *<label></td>
              <td><input name="user" type="text" id="user" /></td>
            </tr>
            <tr>
              <td><label>Contraseña *</label></td>
              <td><input name="pass" type="password" id="pass" /></td>
            </tr>
			<tr>
              <td><label>Confirmar Contraseña *</label></td>
              <td><input name="pass2" type="password" id="pass2" /></td>
            </tr>
            <tr>
              <td><label>Compañia *</label></td>
              <td>
                <select multiple name="compania[]">
                <? $db->select_list("SELECT * FROM vigia.compania WHERE borrado='false' ORDER BY compania","id_compania","compania"); ?>
                </select>
              </td>
            </tr>
            <tr>
              <td><label>Perfil *</label></td>
              <td><select name="perfil">
               <? $db->select_list("SELECT * FROM vigia.perfil","id_perfil","nombre"); ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="2">
                  <button type="submit" form="form1">Guardar y Siguiente</button>
              </td>
            </tr>
          </table>
            <input type="hidden" name="id" value="1" />
            <input type="hidden" name="task" value="save-user" />
        </form>
    </section>
    </body>

<?php
}

function saveAsignFleet($companias){
	global $id, $db;
	$sql = $db->query("SELECT id_compania, compania FROM vigia.compania WHERE id_compania IN (".$companias.")");
    echo $companias;

?>

    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jq_tree/library/jquery-1.4.4.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jq_tree/library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.checkboxtree.js"></script>
	<link rel="stylesheet" type="text/css"
          href="<?=SITE_PATH?>/include/scripts/js/jq_tree/library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css"/>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/jquery.checkboxtree.css" />
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.cookie.js"></script>
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
                    if(response.type == 'ERROR:'){
                        alert(response.type+'\n\n'+response.text);
                    }else{
                        alert(response.type+'\n\n'+response.text);
                        parent.location.reload();
                    }
                    form.find('button[type=submit]').removeAttr('disabled');
                }
            });
            return false;
        });	
	     
	   $('#tree4').checkboxTree({
            collapseUiIcon: 'ui-icon-plus',
            expandUiIcon: 'ui-icon-minus',
            leafUiIcon: 'ui-icon-bullet'
        });
    });
	</script>
        
    </head>
    <body class="white">
    <section class="users-new">
        <h3>Asignación de flotas y unidades</h3>
        <div class="steps">
        	<div class="step-2"><h6>Paso 1:</h6><small>Ingrese los datos del usuario</small></div>
            <div class="step-1"><h6>Paso 2:</h6><small>Asigne las flotas correspondientes</small></div>
            <div class="clear"></div>
        </div>
        <div class="container">
		            
			<form id="form" action="ingreso.php" class="tree">
                <ul id="tree4">
				<?php
				echo "<ul>";
				 	while($c = pg_fetch_object($sql))
				{       
    						echo "<li><input type=\"checkbox\"><label class='tree'>".$c->compania."</label>";
    						/*********************FLOTA***********************/
							$sql2 = getFleet($c->id_compania,0);
    						echo "<ul>";
    						
    						while($f = pg_fetch_object($sql2))
    						{
    							echo "<li><input type=\"checkbox\" name=\"flota[]\" value=".$f->id_flota." ".returnChecked(getUserFlota($id, $f->id_flota))."><label class='tree'>".$f->nombre_flota."</label>";
    							/*****************SUBFLOTA****************/
								$sql3 = getSubFleet($f->id_flota,0);
    							echo "<ul>";
    								
    								while ($sf = pg_fetch_object($sql3))
    								{
    									echo "<li><input type=\"checkbox\" name=\"subflota[]\" value=".$sf->id_flota." ".returnChecked(getUserSubFlota($id, $sf->id_flota))."><label class='tree'>".$sf->nombre_flota."</label>";
    									/********************UNIDAD de SUBFLOTA******************/
										$sql5 = getUnitFleet($sf->id_flota,0);
    									echo "<ul>";
    									
    									while ($un1 = pg_fetch_object($sql5))
    									{
    										echo "<li><input type=\"checkbox\" name=\"unidades1[]\" value=".$un1->id_unidad." ".returnChecked(getUserUnidad($id, $un1->id_unidad))."><label class='tree'>".$un1->nombre."</label>";
    									}
    									echo "</ul>";
    																	
    								}
    							echo "</ul>";						
										/*******************UNIDAD de FLOTA***********************/
    									$sql4 = getUnitFleet($f->id_flota,0);
    									echo "<ul>";
    							while ($un2 = pg_fetch_object($sql4))
    							{
    								echo "<li><input type=\"checkbox\" name=\"unidades2[]\" value=".$un2->id_unidad." ".returnChecked(getUserUnidad($id, $un2->id_unidad))."><label class='tree'>".$un2->nombre."</label>";
    							}
    							echo "</ul>";
    						} 
    					   	echo "</ul>";
				}
				echo "</ul>";
				?>
																		
                <div class="clear20"></div>
                <button type="submit" form="form">Guardar datos</button>
                <input type="hidden" name="id" value="<?=$id?>" />
                <input type="hidden" name="task" value="save-asign-fleet" />
            </form>
         </div>
     </section>
    </body>


<?php
}

function profile(){
	global $db;
	
	$sql = "SELECT * FROM vigia.perfil ORDER BY nombre";
	$res = $db->query($sql);

?>
    <link rel="shortcut icon" href="<?=SITE_PATH?>/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
    $(function(){
        $("form#form-perfil").submit(function(){
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
                    }else if(response.type == 'OK:'){
                        location='index.html.php?task=edit-profile&id='+response.id;
                    }
                    form.find('button[type=submit]').removeAttr('disabled');
                }
            });
            return false;
        });	
    });
    </script>
    </head>
    <body class="white">
    <section class="">
        <div id="boxed">
            <div class="newperfil" >
                <div class="containernewperfil">
                    <div class="headernew">Crear nuevo perfil</div>
                    <form id="form-perfil" method ="post" action="ingreso.php">
                        <div class="nameperfil">
                        <div class="clear10"></div>
                            <label for="perfil">Nombre Perfil:</label>
                            <input type="text" name="perfil" id="perfil" />
                        </div>
                        <!--<label class="radioperfil" for="perfil">Es super Usuario:</label>
                        <input style="margin-left: 20px;" type="checkbox" name="SU" id="SU" value="1" />-->
                        <input type="hidden" name="task" value="create-profile" />
                    </form>
                    <button type="submit" form="form-perfil">Siguiente</button>
                </div>
            </div>
            <div class="editperfil">
                <div class="containereditperfil">
                    <div class="headeredit">Editar un perfil</div>
                    <form id="form" action="index.html.php" method="post">
                        <div class="nameedit" >
                        <label for="id_perfil">Seleccione Perfil:</label>
                        <select name="id" id="id">
                            <?php while($r = pg_fetch_object($res)): ?>
                            <option value="<?=$r->id_perfil?>"><?=$r->nombre?></option>
                            <?php endwhile; ?>
                        </select>
                        </div>
                        <input type="hidden" name="task" value="edit-profile" />
                    </form>
                    <button type="submit" form="form">Editar</button>
                </div>
            </div>
            <div class="clear"></div>
         </div>
    </section>
    </body>

<?php
}

function editProfile(){
	global $db, $id;
	
	$sql = "SELECT * FROM vigia.menu WHERE tipo='compania'";
	$res = $db->query($sql);
	
	$sql3 = "SELECT * FROM vigia.menu WHERE tipo='flota'";
	$res3 = $db->query($sql3);
	
	$sql4 = "SELECT * FROM vigia.menu WHERE tipo='subflota'";
	$res4 = $db->query($sql4);
	
	$sql5 = "SELECT * FROM vigia.menu WHERE tipo='unidad'";
	$res5 = $db->query($sql5);
	
	
	$sql2 = "SELECT * FROM vigia.menu_has_perfil WHERE perfil_id_perfil=$id";
	$res2 = $db->query($sql2);
	
	 while($m2 = pg_fetch_object($res2))
	 {
		$perm[] = $m2->menu_id_menu;
	 }

?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
    $(function(){
        
        $("form").submit(function(){
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
                data: $(this).serialize(),
                success: function(data){
					var response = $.parseJSON(data);
					if(response.type == 'ERROR:'){
						alert(response.type+'\n\n'+response.text);
					}else{
						parent.location.reload();
					}
                    form.find('button[type=submit]').removeAttr('disabled');
                }
            });
            return false;
        });	
        
    });
    
    function seleccionar_todo(){ 
       for (i=0;i<document.f1.elements.length;i++) 
          if(document.f1.elements[i].type == "checkbox")    
             document.f1.elements[i].checked=1 
    }
    function deseleccionar_todo(){ 
       for (i=0;i<document.f1.elements.length;i++) 
          if(document.f1.elements[i].type == "checkbox")    
             document.f1.elements[i].checked=0 
    } 
	function eliminar_perfil(id){
		if(!confirm('Esta seguro que desea eliminar este PERFIL?')) {return false;} 
		else {
			$.post('ingreso.php', {task: 'delete-profile', id: id}, function(data){
				parent.location.reload();
			}, "json");
			return false;
		} 
	}
    </script>
    <style type="text/css">
    button {
      height: 42px;
      width: 213px;
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
      margin: 5px;
      font: normal normal 14px 'Open Sans', sans-serif;
    }
    button:hover {
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
    button:active {
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
    
    </style>
    </head>
    <body class="white">
        <div style="width:100%;height:60px;background:#f7f7f7;">
            <div style="color:#22aae4;padding:14px 15px;font: normal normal 25px 'Open Sans', sans-serif;">Permisos</div>
        </div>
        <form id="f1" name="f1" method="POST" action="ingreso.php">
        <div style="width:99.8%;height:300px;border:1px solid #DBDBDB;">
            <div style="width:49.8%;height:150px;border-right:1px solid #DBDBDB; border-bottom:1px solid #DBDBDB;float:left">
                <div style="width:100%%;height:26px;border-bottom:1px solid #DBDBDB;color:#22aae4;font: normal normal 15px/24px 'Open Sans', sans-serif;text-align:center;background:#f4f4f4">Compañia</div>
                <div style="padding:18px">
                    <?php while($m = pg_fetch_object($res)): ?>
                    <input type="checkbox" value="<?=$m->nombre?>"<?php echo returnChecked(getPermisoMenu($id, $m->nombre));?> name="options[]"/><div style="font-size: 13px;margin-top: -20px;margin-left: 17px;"><?=$m->nombre?></div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div style="width:50%;height:150px;border-bottom:1px solid #DBDBDB;float:left">
                <div style="width:100%%;height:26px;border-bottom:1px solid #DBDBDB;color:#22aae4;font: normal normal 15px/24px 'Open Sans', sans-serif;text-align:center;background:#f4f4f4">Flota</div>
                <div style="padding:18px">
                    <?php while($m = pg_fetch_object($res3)): ?>
                    <input type="checkbox" value="<?=$m->nombre?>"<?php echo returnChecked(getPermisoMenu($id, $m->nombre));?> name="options[]"/><div style="font-size: 13px;margin-top: -20px;margin-left: 17px;"><?=$m->nombre?></div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div style="width:49.8%;height:150px;border-right:1px solid #DBDBDB;float:left">
                <div style="width:100%%;height:26px;border-bottom:1px solid #DBDBDB;color:#22aae4;font: normal normal 15px/24px 'Open Sans', sans-serif;text-align:center;background:#f4f4f4">Sub-flota</div>
                <div style="padding:18px">
                    <?php while($m = pg_fetch_object($res4)): ?>
                    <input type="checkbox" value="<?=$m->nombre?>" <?php echo returnChecked(getPermisoMenu($id, $m->nombre));?> name="options[]"/><div style="font-size: 13px;margin-top: -20px;margin-left: 17px;"><?=$m->nombre?></div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div style="width:50%;height:150px;float:left"> 
                <div style="width:100%%;height:26px;border-bottom:1px solid #DBDBDB;color:#22aae4;font: normal normal 15px/24px 'Open Sans', sans-serif;text-align:center;background:#f4f4f4">Unidad</div>
                <div style="padding:18px">
                    <?php while($m = pg_fetch_object($res5)): ?>
                    <input type="checkbox" value="<?=$m->nombre?>" <?php echo returnChecked(getPermisoMenu($id, $m->nombre));?> name="options[]"/><div style="font-size: 13px;margin-top: -20px;margin-left: 17px;"><?=$m->nombre?></div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
                <button type="submit" form="f1">GUARDAR DATOS DE PERFIL</button>
                <!--input type='submit' value='GUARDAR DATOS DE PERFIL' /-->
                <a href="javascript:seleccionar_todo()">Marcar todos</a> | 
                <a href="javascript:deseleccionar_todo()">Marcar ninguno</a>| 
                <a onClick="eliminar_perfil(<?=$id?>)" href="#">Eliminar perfil</a>
                
                <input type="hidden" name="tp" value="update" />
                <input type="hidden" name="task" value="update-profile" />
                <input type="hidden" name="unit" value="<?=$id?>" />
                 
            </form>
    </body>

<?php
}

function editUser(){
	global $id, $db;
	include "usuario.php";
	$usr = new Usuario();
	$usr->usuarioxID($id, $db);
    $company= genera_array($usr->compania($db));
	
    $sql = $db->query("SELECT id_compania, id_usuario FROM vigia.permisos_arbol WHERE id_usuario=".$id);
	$sql2 = $db->query("SELECT id_perfil, nombre FROM vigia.perfil ORDER BY nombre");
?>
    <link rel="shortcut icon" href="<?=SITE_PATH?>/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfx.min.js"></script>
    <script type="text/javascript">
    $(function(){
        $("form").submit(function(){
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
                data: $(this).serialize(),
                success: function(data){
                    var response = $.parseJSON(data);
                    if(response.type == 'ERROR:'){
                        alert(response.type+'\n\n'+response.text);
                    }else if(response.type == 'OK:'){
                        location='<?=SITE_PATH?>/users/index.html.php?task=save-asign-fleet&id='+response.id+'&companias='+response.companias;
                    }
                    form.find('button[type=submit]').removeAttr('disabled');
                }
            });
            return false;
        });	
    });
    </script>
    </head>
    <body class="white">
    <section class="users-new">
        <h3>Editar usuario</h3>
        <div class="steps">
            <div class="step-1"><h6>Paso 1:</h6><small>Ingrese los datos del usuario</small></div>
            <div class="step-2"><h6>Paso 2:</h6><small>Asigne las flotas correspondientes</small></div>
            <div class="clear"></div>
        </div>
        <form id="form1" name="form1" method="post" action="ingreso.php">
          <table border="0">
            <tr>
              <td><label>Nombre </label></td>
              <td><input name="nombre" type="text" id="nombre" value="<? echo $usr->get_nombre(); ?>" /></td>
            </tr>
            
            <tr>
              <td><label>Teléfono </label></td>
              <td><input name="telefono" type="text" id="telefono" value="<? echo $usr->get_telefono(); ?>"/></td>
            </tr>
            <tr>
              <td><label>E-mail </label></td>
              <td><input name="email" type="text" id="email" value="<? echo $usr->get_mail(); ?>"/></td>
            </tr>
            <tr>
              <td><label>Nombre usuario</label></td>
              <td><input name="user" type="text" id="user" value="<? echo $usr->get_user(); ?>"/></td>
            </tr>
             <? if($company[0] <> "0"){ 
             $sql_compania=getCompanias("");
             echo "<tr>
              <td><label>Compañia </label></td>
              <td>
                <select multiple name=\"compania[]\">";
                    while($row_c = pg_fetch_object($sql_compania))
                        echo "<option value=".$row_c->id_compania." ".returnSelected(getUserCompany($id, $row_c->id_compania)).">".$row_c->compania."</option>";
                        //foreach ($company as &$valor)
                          //  echo "<option ".returnSelected(getUserCompany($id, $valor)).">".getCompanyName($valor)."</option>";
                    
                echo "</select>
              </td>
            </tr>";
            } ?>
            <tr>
              <td><label>Perfil</label></td>
              <td><select name="perfil">
					   <?php while($d = pg_fetch_object($sql2)): ?>
                       <option <?php if($usr->get_perfil() == $d->id_perfil) echo 'selected'; ?> value="<?=$d->id_perfil?>"><?=$d->nombre?></option>
                       <?php endwhile; ?>
              </select></td>
            </tr>
			<tr>
              <td><label>Activo </label></td>
              <td><input name="activo" type="checkbox"  <?=returnChecked(getUserActivo($id));?> ></td>
			</tr>
            <tr>
              <td colspan="2">
                  <button type="submit" form="form1">Actualizar y Seguir</button>
              </td>
            </tr>
          </table>
          <input name="id" type="hidden" value="<?=$id; ?>" />
          <input name="task" type="hidden" value="update-user" />
        </form>
    </section>
    </body>
<?php
}
function editPassUser(){
	global $id, $db;
	
?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfx.min.js"></script>
	<!--<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/valida_usr.js"></script>-->
    <script type="text/javascript">
	$(function(){
		$('body').css('background-color', 'white');
		
        $("form").submit(function(){
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                beforeSend: function(){ form.find('button[type=submit]').attr('disabled', 'disabled');},
                data: $(this).serialize(),
                success: function(data){
                    var response = $.parseJSON(data);
					if(response.type=='ERROR:'){
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
                <h2>Editar Contraseña</h2>
            </div>
            <div class="content">
                <form id="form1" name="form1" action="ingreso.php">
                  <table border="0">
                    <tr>
                      <td><label>Nueva Contraseña </label></td>
                      <td colspan="2"><input type="password" name="pass1" type="text" id="pass1" /></td>
                    </tr>
                    <tr>
                      <td><label>Verificar Contraseña </label></td>
                      <td><input type="password" name="pass2" type="text" id="pass2" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="id" value="<?=$id;?>" />
                  <input type="hidden" name="task" value="update-pass" />
                </form>
                <button type="submit" form="form1">Actualizar</button>
           </div>
        </div>
    </section>
    
<?php
}
?>
