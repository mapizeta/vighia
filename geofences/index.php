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
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/panel/fontsdigital.css">
<link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/include/scripts/js/treeview/jquery.treeview.css">
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.livequery.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/treeview/jquery.treeview.js"></script>
<script type="text/javascript">
var site_path = 'http://vpro.no-ip.biz/vighia2/';

$(function(){
	
$('#visibilidad_geo').click(function(){
if (this.checked) 
	{
	
	$('#map-canvas').empty();
	initialize();
}
else{ 
	
	initialize();
}
});

$('.geocerca').click(function(){

	var latlon = $(this).attr('latlon');
	var latlon_array = latlon.split(',');
	var lat = latlon_array[0];
	var lon = latlon_array[1];
	var b = new google.maps.LatLng(lat,lon);
   
    map.setCenter(b);
});	

	$(".navigation").treeview({
		persist: "location",
	});

	//INICIO: Abre y cierra el menu lateral para navegar entre los elementos
/*	$('ul li:has(ul) > a').click(function(){
		$(this).siblings('ul.fleet, ul.sub-fleet, ul.unit').slideToggle();
		$(this).toggleClass('icon-less');
	});
	$('ul.sub-fleet li:has(ul) > a').click(function(){
		$(this).siblings('ul.sub-unit').slideToggle();
	});
*/	//FIN: Abre y cierra el menu lateral para navegar entre los elementos
	
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
				$(this).append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><li><a href="index.html.php?task=edit-group&id='+id+'" class="modal">Editar grupo</a></li><li><a onclick="deleteGroup('+id+')">Eliminar grupo</a></li></ul></div>');
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
					//?id_unidad=<?php echo $un1->id_unidad?>&op=2
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

//***************************************FUNCIONES MAPA**********************************************************************
var map, marker, myLatlng, circles = [];

function initialize() {
	var myLatLng = new google.maps.LatLng(-38.740368, -72.596275);
	var mapOptions = {
	zoom: 12,
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
	
		
	//***************************************FUNCIONES DIBUJO********************************************************************

var drawingManager = new google.maps.drawing.DrawingManager({
	drawingMode: google.maps.drawing.OverlayType.POLYLINE,
	drawingControl: true,
	drawingControlOptions: {
		position: google.maps.ControlPosition.TOP_CENTER,
		drawingModes: [google.maps.drawing.OverlayType.POLYLINE, google.maps.drawing.OverlayType.MARKER, google.maps.drawing.OverlayType.POLYGON, google.maps.drawing.OverlayType.CIRCLE ]
	},
	polylineOptions: {
		strokeWeight: 2,
		strokeColor: '#ee9900',
		clickable: false,
		zIndex: 1,
		editable: false
	},
	polygonOptions: {
		editable:false
	}
});

	drawingManager.setMap(map);
	
	//INICIO: Guardar marcador
	google.maps.event.addDomListener(drawingManager, 'markercomplete', function(marker) {
		document.getElementById("action").value += "Marker;\n";
		document.getElementById("action").value += marker.getPosition() + ";\n";
		$.fancybox.open([
			{
				href : 'index.html.php?task=save-geo&action='+$('#action').val(),
				'type' : 'iframe',
				'autoSize': 'true',
				afterClose: function() {
					location.reload();
				}
			}   
		]);
		return false;
	});
	//FIN: Guardar marcador
	
	//INICIO: Guardar rutas
	google.maps.event.addDomListener(drawingManager, 'polylinecomplete', function(line) {
		path = line.getPath();
		document.getElementById("action").value += "Polyline;\n";
		for(var i = 0; i < path.length; i++) {
			document.getElementById("action").value += path.getAt(i) + ";\n";
		}
		$.fancybox.open([
			{
				href : 'index.html.php?task=save-geo&action='+$('#action').val(),
				'type' : 'iframe',
				'autoSize': 'true',
				afterClose: function() {
					location.reload();
				}
			}   
		]);
		return false;
	});
	//FIN: Guardar rutas
	
	//INICIO: Guardar poligonos
	google.maps.event.addDomListener(drawingManager, 'polygoncomplete', function(polygon) {
		path = polygon.getPath();
		document.getElementById("action").value += "Polygon;\n";
		for(var i = 0; i < path.length; i++) {
			document.getElementById("action").value += path.getAt(i) + ';\n';
		}
		$.fancybox.open([
			{
				href : 'index.html.php?task=save-geo&action='+$('#action').val(),
				'type' : 'iframe',
				'autoSize': 'true',
				afterClose: function() {
					location.reload();
				}
			}   
		]);
		return false;
	});
	//FIN: Guardar piligonos
	
	//INICIO: Guardar circulo
	google.maps.event.addDomListener(drawingManager, 'circlecomplete', function(circle) {
		circles.push(circle);
		for (var i = 0; i < circles.length; i++) {
			var circleCenter = circles[i].getCenter();
			var circleRadius = circles[i].getRadius();
			document.getElementById("action").value += "Circle;\n((";
			document.getElementById("action").value += 
			circleCenter.lat().toFixed(3) + "," + circleCenter.lng().toFixed(3);
			document.getElementById("action").value += "), ";
			document.getElementById("action").value += circleRadius.toFixed(3) + ")\n";
		}
		$.fancybox.open([
			{
				href : 'index.html.php?task=save-geo&action='+$('#action').val(),
				'type' : 'iframe',
				'autoSize': 'true',
				afterClose: function() {
					location.reload();
				}
			}   
		]);
		return false;
	});
	//FIN: Guardar circulo
	
  
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	drawingManager.setMap(map);

	//FIN: FUNCIONES DIBUJO

	//****************MUESTRA GEOCERCAS BASE DATOS*********************************
	if($("#visibilidad_geo").is(':checked')) {  
            
<?php
	$q_geofence="SELECT * FROM vigia.geofences";
	
	$geofence=$db->query($q_geofence);
	while ($q_geofence = pg_fetch_object($geofence)){
		imprime_geofence($q_geofence->id_geofence,$q_geofence->fk_id_tipo,$cont);
		$cont++;
	}
?>}
 //FIN: MUESTRA GEOCERCAS

}

function inhabilitar(){
    return false
}
document.oncontextmenu=inhabilitar

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
<body class="heigth" onLoad="initialize()">

<header >
    <img class="logo" src="../images/logo-home.png" width="162" height="44" alt="Logo Vighia">
    <nav>
        <ul>
            <li><a id="fleets" href="<?=SITE_PATH?>/home">FLOTAS</a></li>
            <li class="active"><a id="fences" class="fences-active" href="#">GEOCERCAS</a><span></span></li>
            <li><a id="users" href="<?=SITE_PATH?>/users">USUARIOS</a></li>
        </ul>
    </nav>
    <a class="logout" href="<?=SITE_PATH?>/fin_sesion.php">Salir</a>
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
	<aside>
    	<div class="clear20"><input type="checkbox" checked="checked" name="visibilidad_geo" id="visibilidad_geo"><label class="bkg_negro">Visibilidad</label>
	
		<?php $cont=0; while($c = pg_fetch_object($sql)): $cont++; ?>
        <ul class="geo navigation">
            <li class="border"><a class="com" data-company="<?=$c->id_compania?>" href="#<?=$cont?>"><?=$c->compania?></a>
                <?php $sql2 = getGroup($c->id_compania);
                while($f = pg_fetch_object($sql2)): $cont++; ?>
                <ul class="fleet hide">
                    <li><a class="menu" data-group="<?=$f->id_grupos?>" href="#<?=$cont?>"><?=$f->grupo?></a>
                    	<ul class="sub-fleet hide">
						<?php $sql3 = getGeofence($f->id_grupos);
                        while ($geo = pg_fetch_object($sql3)): $cont++; ?>
                            <li><a class="uni geocerca" data-geo="<?=$geo->id_geofence?>" latlon="<?php echo primera_coord($geo->id_geofence);?>" href="#<?=$cont?>" style="background:url(../images/icons/icon_geocerca.png) no-repeat left center"><?=$geo->nombre?></a></li>
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
    <section class="wrapper" >
    	<textarea hidden id="action" name="action" rows="8" cols="46"></textarea>
        <div id="map-canvas" style="height:96.5%;background:rgba(246,246,246); width: 98%;">
    </section>
	<div class="clear"></div>
</section>

</body>
</html>