<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/security/XSS.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';
include 'usuario.php';
?>
<!doctype html>
<!--[if IE 6]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if (gt IE 6)|!(IE)]><!-->
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title><?=SITE_NAME?></title>
<meta name="author" content="ROWSIS especialista en aplicaciones multiplataforma">
<link rel="shortcut icon" href="<?=SITE_PATH?>/images/favicon.ico">
<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>-->
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.css">
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
<!--<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfx.min.js"></script>-->
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.uitablefilter.js"></script>
<script type="text/javascript">
$(function() {
	theTable = $("#t_user");
		$("#q").keyup(function() {
		$.uiTableFilter(theTable, this.value);
	});
	
	$('.modal').fancybox();	
	
	$('.actions').click(function(){
		var menu = $(this).attr('data-act');
		$(menu).fadeIn('fast');
		
		$(menu).hover(
			function () {
				
			},
			function () {
				$(this).fadeOut('fast');
			}
		);
		
	});
		
});
</script>
</head>
<body>

<header>
    <img class="logo" src="../images/logo-home.png" width="162" height="44" alt="Logo Vighia">
    <nav>
        <ul>
            <li><a id="fleets" href="<?=SITE_PATH?>/home">FLOTAS</a></li>
            <li><a id="fences" href="<?=SITE_PATH?>/geofences">GEOCERCAS</a></li>
            <li class="active"><a id="users" class="users-active" href="<?=SITE_PATH?>/users">USUARIOS</a><span></span></li>
        </ul>
    </nav>
    <a class="logout" href="<?=SITE_PATH?>/fin_sesion.php">Salir</a>
    <div class="menu-settings">
    	<ul>
        	<li><a href="#">Salir</a></li>
        </ul>
    </div>
    <div class="clear"></div>
</header>

<section class="users" >
    <div class="header1">
    	<div class="leyenda-usuario" >USUARIOS</div>
    </div>
    <div class="header2">
        <div class="buscador" >
            Buscar: <input type="text" id="q" name="q" value="" />
        </div>
        <?php if($_SESSION["profile"] == 1): echo '
			<div class="btnusuario">
				<a class="button modal" data-fancybox-type="iframe" href="index.html.php?task=save-user">Agregar Usuarios</a>
				<a class="button modal" data-fancybox-type="iframe" href="index.html.php?task=profile">Perfiles</a>
			</div>';
		endif;?>
    </div>
    
    <div class="datagrid" style="margin: 20px;">
        <table id="t_user">
            <thead>
                <tr>
                    <th style="border-bottom:1px solid #797979;">#</th>
                    <th style="border-bottom:1px solid #797979;">USUARIO</th>
                    <th style="border-bottom:1px solid #797979;">ACTIVO</th>
                    <th style="border-bottom:1px solid #797979;">PERFIL</th>
                    <th style="border-bottom:1px solid #797979;">NOMBRE</th>
                    <th style="border-bottom:1px solid #797979;">TELEFONO</th>
                    <th style="border-bottom:1px solid #797979;">E-MAIL</th>
                    <th style="border-bottom:1px solid #797979;">COMPA&Ntilde;IA</th>
                    <th style="border-bottom:1px solid #797979;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
				<?php
                $res=$db->query("SELECT * FROM vigia.usuario WHERE borrado=false ORDER BY nombre");
                $i=1;
                $x=0;
                while ($row = pg_fetch_object($res)){
					$i=$i+1;
					$x=$x+1;
					if($i%2==0) 
					echo("<tr  bgcolor= #f4f4f4 >");
					
					else
					echo("<tr  bgcolor= #EBEBEB  >");
					//print_r($row);
					$usr=new Usuario();
					$usr->set_id($row->id_usuario);
					$usr->set_user($row->usuario);
					$usr->set_nombre($row->nombre);
					$usr->set_telefono($row->telefono);
					$usr->set_mail($row->email);
					$usr->set_perfil($row->perfil_id_perfil);
					if(getUserActivo($row->id_usuario))
					$sts_user = "Si";
					else
					$sts_user = "No";
					/*
					if ($row->activo):
					$sts_user = "Si";
					else:
					$sts_user = "No";
					endif;*/
					
					echo "
					<td>".$x."</td>
					<td>".$row->usuario."</td>
					<td>".$sts_user."</td>
					<td>".$usr->perfil($db)."</td>
					<td>".$row->nombre."</td>
					<td>".$row->telefono."</td>
					<td>".$usr->get_mail()."</td>
					<td>".impr_campo_permisos($row->id_usuario, "compania")."</td>
					<td><div class=\"ancho\" width=\"80px\">";
				if($_SESSION["profile"] == 1): echo "
					<a class='actions button' data-act='#drop".$x."' href='#'>Aciones<span></span></a>
					<ul id='drop".$x."' class='dropdawn'>
					<li><a class=\"edit modal\" data-fancybox-type=\"iframe\" href=\"index.html.php?task=edit-user&id=".$row->id_usuario."\">Editar</a></li>
					<li><a class=\"edit modal\" data-fancybox-type=\"iframe\" href=\"index.html.php?task=edit-pass-user&id=".$row->id_usuario."\">Cambiar contraseña</a></li>
					<li><a class=\"edit modal\" onclick=\"return confirm('¿Está seguro que desea eliminar el usuario?');\" href=\"ingreso.php?task=delete-user&id=".$row->id_usuario."\">Eliminar</a></li>
					</ul>";
				endif; echo "
					</div> 
					</td>
					";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</section>

</body>
</html>
