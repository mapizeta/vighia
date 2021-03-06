<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';
require_once ABS_PATH.'/include/security/XSS.php';
/*VALIDAR USUARIO... SI ES SUPER USUARIO SQL Y SI ES USUARIO NORMAL SQL2 */
$sql = $db->query("SELECT * FROM vigia.compania WHERE borrado=false ORDER BY id_compania");

// $sql2 = $db->query("SELECT * FROM vigia.compania WHERE borrado=false AND id_compania=".id_comp_usr);
if (@$_REQUEST['id_unidad']):
$sql2 = get_lat_lng($_REQUEST['id_unidad']);
$cordenadas = pg_fetch_object($sql2); 
$lat = $cordenadas->lat/1000000;
$lng = $cordenadas->lng/1000000;
endif;

if($id_unidad){
$res_unidad=$db->query("SELECT * FROM vigia.trama WHERE unidad_id_unidad=".$id_unidad);
$unity = pg_fetch_object($res_unidad);
}
$voltaje = ($unity->volt_ext)*0.1;
$voltajebateria = $unity->volt_bat;

$cont=0;

function imprimePolygon($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	echo "var polygono".$cont." = [\n";
	while ($row = pg_fetch_object($res)) 
	        {
		 echo "\n new google.maps.LatLng(".$row->lat.", ".$row->lng.")";
		 if($row)
		 	echo ",";
		    }
	echo "];\n";

	echo "var Poligono".$cont." = new google.maps.Polygon({
		    paths: polygono".$cont.",
		    strokeColor: \"#FF0000\",
		    strokeOpacity: 0.8,
		    strokeWeight: 3,
		    fillColor: \"#FF0000\",
		    fillOpacity: 0.35
		  });\n";
		  
		  echo "Poligono".$cont.".setMap(map);\n";

}

function imprimePolyline($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	echo "var ruta".$cont." = [\n";
	while ($row = pg_fetch_object($res)) 
	        {
		 echo "\n new google.maps.LatLng(".$row->lat.", ".$row->lng.")";
		 if($row)
		 	echo ",";
		    }
	echo "];\n";

	echo "var PolyLine".$cont." = new google.maps.Polyline({
		    path: ruta".$cont.",
		    strokeColor: \"#FF0000\",
	   		strokeOpacity: 1.0,
	    	strokeWeight: 3
		  });\n";
		  
		  echo "PolyLine".$cont.".setMap(map);\n";

}
function imprimeMarker($geofence,$cont){
	global $db;
	$consulta="SELECT * FROM vigia.coordenada WHERE geofence_id_geofence=".$geofence;

	$res=$db->query($consulta);
	$coor = pg_fetch_object($res);
	echo "var punto".$cont." = new google.maps.LatLng(".$coor->lat.", ".$coor->lng.");\n";
	
	echo "var marker".$cont." = new google.maps.Marker({
		    position: punto".$cont.",
		});\n";
		
		
		
	echo "marker".$cont.".setMap(map);\n";
	
}

function imprime_geofence($geofence,$tipo,$cont){

switch ($tipo) {	

	default:
		break;
		
	case 1:
		imprimeMarker($geofence,$cont);
		break;
		
	case 2:
		imprimePolygon($geofence,$cont);
		break;
				
	case 3:
		imprimeCircle($geofence,$cont);
		break;
		
	case 4:
		imprimePolyline($geofence,$cont);
		break;
}
}

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
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/ResponsiveSlides/responsiveslides.css">
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/panel/fontsdigital.css">
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700|Quicksand:300,400' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.livequery.js"></script>
<!--<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfx.min.js"></script>-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<!--script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script-->
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.pack.js"></script>
<script src="<?=SITE_PATH?>/panel/js/highcharts.js"></script>
<script src="<?=SITE_PATH?>/panel/js/highcharts-more.js"></script>
<!-- PARA CARGA DE KML
<script type="text/javascript" src="geoxml3.js"></script>
<script type="text/javascript" src="ProjectedOverlay.js"></script>
-->

<script type="text/javascript">
var site_path = 'http://vpro.no-ip.biz/vighia2/';
$(function(){	

	//INICIO: Abre y cierra el menu lateral para navegar entre los elementos
	$('ul li:has(ul) > a').click(function(){
		$(this).siblings('ul.fleet, ul.sub-fleet, ul.unit').slideToggle();
		$(this).toggleClass('icon-less');
	});
	$('ul.sub-fleet li:has(ul) > a').click(function(){
		$(this).siblings('ul.sub-unit').slideToggle();
	});
	//FIN: Abre y cierra el menu lateral para navegar entre los elementos
	
	//INICIO: Click secundario con las opciones que corresponden al nivel de COMPAÑIA.
	$('a.com').mousedown(function(event) {
		switch (event.which) {
			case 1:
				$('a.active').removeClass('active');
				$(this).addClass('active');
				break;
			case 2:
				//alert('Middle mouse button pressed');
				break;
			case 3:
				var id = $(this).attr('data-company');
				$('div.second-menu').remove();
				$(this).append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><li><a href="index.html.php?task=create-group&id='+id+'" class="modal">Crear grupo</a></li></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel COMPAÑIA
		
	//INICIO: Click secundario con las opciones correspondientes al nivel de GRUPO
	$('a.menu').mousedown(function(event) {
		switch (event.which) {
			case 1:
				$('a.active').removeClass('active');
				$(this).addClass('active');
				break;
			case 2:
				//alert('Middle mouse button pressed');
				break;
			case 3:
				var id = $(this).attr('data-group');
				$('div.second-menu').remove();
				$(this).append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><li><a href="index.html.php?task=create-geofence&id='+id+'" class="modal">Crear geocerca</a></li><li><a href="index.html.php?task=edit-group&id='+id+'" class="modal">Editar grupo</a></li><li><a onclick="deleteGroup('+id+')">Eliminar grupo</a></li></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel GRUPO
	
	
	//INICIO: Click secundario con las opciones correspondientes al nivel de UNIDAD
	$('a.uni').mousedown(function(event) {
		switch (event.which) {
			case 1:
				$('a.active').removeClass('active');
				$(this).addClass('active');
				
				//var id=$(this).attr('data-unit');
				//if(id){
					//?id_unidad=<? echo $un1->id_unidad?>&op=2
				//$.post('<?=SITE_PATH?>/panel/info.php', { op: '2', id_unidad: id } );
				//	return false;
				//}
				break;
			case 2:
				//alert('Middle mouse button pressed');
				break;
			case 3:
                var id = $(this).attr('data-geo');
				$('div.second-menu').remove();
				$(this).append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><li><a href="index.html.php?task=move-geo&id='+id+'" class="modal">Mover geocerca</a></li><li><a onClick="deleteGeo('+id+')">Eliminar geocerca</a></li></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel UNIDAD

	
	//INICIO: Eliminar menu contectual al hacer click en cualquier lugar del documento
	$(document).click(function(){
		$('div.second-menu').remove();
	});
	//FIN: Eliminar menu contectual al hacer click en cualquier lugar del documento
	
	//INICIO: Click para abrir opciones en el menu contextual
	$('.modal').livequery('click', function(event){ 
		var href = $(this).attr('href');		
		$.fancybox.open([
			{
				href : href,
				'type' : 'iframe',
				'autoSize': 'true'
			}   
		]);
		$('div.second-menu').remove(); 
		return false; 
	});
	//FIN: Click para abrir opciones en el menu contextual
	
	//INICIO: boton para expandir el menu lateral
	$('#expand').livequery('click', function(event){
		$('.hide').css('display', 'block');
		$('div.second-menu').remove();
		$('ul li a').addClass('icon-less');
		return false;
	});
	//FIN: boton para expandir el menu lateral
	
	//INICIO: boton para contraer el menu lateral
	$('#collapse').livequery('click', function(event){
		$('.hide').css('display', 'none');
		$('div.second-menu').remove();
		$('ul li a').removeClass('icon-less');
		return false;
	});
	//FIN: boton para contraer el menu lateral

});
var map, marker, myLatlng;

function initialize() {
  var myLatLng = new google.maps.LatLng(-38.740368, -72.596275);
  var mapOptions = {
    zoom: 4,
      center: new google.maps.LatLng(-38.740368, -72.596275),
      	streetViewControl: false,
	    panControl: false,
	    panControlOptions: {
	        position: google.maps.ControlPosition.TOP_RIGHT
	    },
	    zoomControl: true,
	    zoomControlOptions: {
	      style: google.maps.ZoomControlStyle.DEFAULT,
	      position: google.maps.ControlPosition.TOP_RIGHT
	    },
          mapTypeId: google.maps.MapTypeId.HYBRID
  };
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  
<?
	$q_geofence="SELECT * FROM vigia.geofences";
	
	$geofence=$db->query($q_geofence);
	while ($q_geofence = pg_fetch_object($geofence)){
		imprime_geofence($q_geofence->id_geofence,$q_geofence->fk_id_tipo,$cont);
		$cont++;
	}
?>
  
}

google.maps.event.addDomListener(window, 'load', initialize);



<!--
function inhabilitar(){
    return false
}
document.oncontextmenu=inhabilitar
// -->


function deleteGeo(id){
	if(!confirm('Esta seguro que desea eliminar esta GEOCERCA?')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-geo', id: id}, function(data){
			alert(data.type+'\n\n'+data.text);
			location.reload();
		}, "json");
		return false;
	} 
}
function deleteGroup(id){
	if(!confirm('Esta seguro que desea eliminar este GRUPO?')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-group', id: id}, function(data){
			alert(data.type+'\n\n'+data.text);
			location.reload();
		}, "json");
		return false;
	} 
}


</script> 
</head>
<body onLoad="initialize()">

<header >
    <img class="logo" src="../images/logo-home.png" width="162" height="44" alt="Logo Vighia">
    <nav>
        <ul>
            <li><a id="fleets" href="<?=SITE_PATH?>/home">FLOTAS</a></li>
            <li class="active"><a id="fences" class="fences-active" href="#">GEOCERCAS</a><span></span></li>
            <li><a id="users" href="<?=SITE_PATH?>/users">USUARIOS</a></li>
        </ul>
    </nav>
    <a class="logout" href="#">Salir</a>
    <div class="clear"></div>
</header>

<style type="text/css">

hr {
    border: 0;
    height: 1px;
    background-image: -webkit-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:    -moz-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:     -ms-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
    background-image:      -o-linear-gradient(left, rgba(34,170,228,0), rgba(34,170,228,0.75), rgba(34,170,228,0)); 
}

.hrdialog{
    border: 0;
    height: 1px;
    background-image: -webkit-linear-gradient(left, rgba(255,255,255,0), rgba(255,255,255,0.75), rgba(255,255,255,0)); 
    background-image:    -moz-linear-gradient(left, rgba(255,255,255,0), rgba(255,255,255,0.75), rgba(255,255,255,0)); 
    background-image:     -ms-linear-gradient(left, rgba(255,255,255,0), rgba(255,255,255,0.75), rgba(255,255,255,0)); 
    background-image:      -o-linear-gradient(left, rgba(255,255,255,0), rgba(255,255,255,0.75), rgba(255,255,255,0)); 
}
</style>

<section class="main">
	<aside >
    	<div class="clear20"></div>
	
		<?php while($c = pg_fetch_object($sql)): ?>
        <ul class="geo">
            <li class="border"><a class="com" data-company="<?=$c->id_compania?>" href="#"><?=$c->compania?></a>
                <?php $sql2 = getGroup($c->id_compania);
                while($f = pg_fetch_object($sql2)): ?>
                <ul class="fleet hide">
                    <li><a class="menu" data-group="<?=$f->id_grupos?>" href="#"><?=$f->grupo?></a>
                    	<ul class="sub-fleet hide">
						<?php $sql3 = getGeofence($f->id_grupos);
                        while ($geo = pg_fetch_object($sql3)):?>
                            <li><a class="uni" data-geo="<?=$geo->id_geofence?>" href="#" style="background:url(../images/icons/icon_geocerca.png) no-repeat left center"><?=$geo->nombre?></a></li>
                        <?php endwhile; ?>
                        </ul>
                    </li>
                </ul>
                <?php endwhile; ?>
            </li>
            <hr>
        </ul>
        <?php endwhile; ?>
        
    </aside>
	    <!--div id="mapakml" style="width:600px; height:600px">  
    <script type="text/javascript">mapaKML();</script>  
    </div-->  
    <section class="wrapper" >
    	
        <div id="map-canvas" style="height:550px;background:rgba(246,246,246);">

        </div>

</section>

<footer>
</footer>

</body>
</html>


