<?php
require_once '../config.php';
require_once ABS_PATH.'/include/conect.php';
require_once ABS_PATH.'/include/scripts/php/functions.php';
require_once ABS_PATH.'/include/security/XSS.php';

$query = $db->query("SELECT * FROM vigia.permisos_arbol WHERE id_usuario=".$_SESSION["userid"]);
$p = pg_fetch_object($query);

if($p->id_compania != 0) $where = "AND id_compania IN (".$p->id_compania.")";

$sql = getCompanias($where);
//$sql = $db->query("SELECT * FROM vigia.compania WHERE borrado=false $where ORDER BY id_compania");

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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/treeview/jquery.treeview.js"></script>
<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery.epiclock.min.js"></script>
<script type="text/javascript">
var site_path = '<?=SITE_PATH?>/';
$(function(){	

	$("#navigation").treeview({
		control: "#treecontrol"
	});
		
	//INICIO: Click secundario con las opciones que corresponden al nivel de MASTER.
	$('a.principal').mousedown(function(event) {
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
				$(this).parent().append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><?php if(getPermisoMenu($_SESSION["profile"], "compania_crear")) echo "<li><a href=\"index.html.php?task=create-company&id='+id+'\" class=\"modal\">Crear compañia</a></li>"; ?></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel MASTER
		
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
				$(this).parent().append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><?php if(getPermisoMenu($_SESSION["profile"], "compania_editar")) echo "<li><a href=\"index.html.php?task=edit-company&id='+id+'\" class=\"modal\">Editar compañia</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "flota_crear")) echo "<li><a href=\"index.html.php?task=create-fleet&id='+id+'\" class=\"modal\">Crear flota</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "compania_eliminar")) echo "<li><a onclick=\"deleteCompany('+id+')\">Eliminar compañia</a></li>"; ?></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel COMPAÑIA
	
	//INICIO: Click secundario con las opciones correspondientes al nivel de FLOTAS
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
				var id = $(this).attr('data-fleet');
				$('div.second-menu').remove();
				$(this).parent().append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><?php if(getPermisoMenu($_SESSION["profile"], "unidad_crear")) echo "<li><a href=\"index.html.php?task=create-unit&id='+id+'\" class=\"modal\">Crear Unidad</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "subflota_crear")) echo "<li><a href=\"index.html.php?task=create-subfleet&id='+id+'\" class=\"modal\">Crear Subflota</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "flota_editar")) echo "<li><a href=\"index.html.php?task=edit-fleet&id='+id+'\" class=\"modal\">Editar flota</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "flota_eliminar")) echo "<li><a onclick=\"deleteFleet('+id+')\">Eliminar flota</a></li>"; ?></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel FLOTAS
	
	//INICIO: Click secundario con las opciones correspondientes al nivel de SUBFLOTAS
	$('a.submenu').mousedown(function(event) {
		switch (event.which) {
			case 1:
				$('a.active').removeClass('active');
				$(this).addClass('active');
				break;
			case 2:
				//alert('Middle mouse button pressed');
				break;
			case 3:
                var id = $(this).attr('data-subfleet');
				$('div.second-menu').remove();
				$(this).parent().append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><?php if(getPermisoMenu($_SESSION["profile"], "unidad_crear")) echo "<li><a href=\"index.html.php?task=create-unit&id='+id+'\" class=\"modal\">Crear Unidad</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "subflota_editar")) echo "<li><a href=\"index.html.php?task=edit-fleet&id='+id+'\" class=\"modal\">Editar SubFlota</a></li>"; ?><?php if(getPermisoMenu($_SESSION["profile"], "subflota_eliminar")) echo "<li><a onclick=\"deleteSubfleet('+id+')\">Eliminar SubFlota</a></li>"; ?></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
			default:
				//alert('You have a strange mouse');
		}
	});
	//FIN: Click secundario nivel SUBFLOTAS
	
	//INICIO: Click secundario con las opciones correspondientes al nivel de UNIDAD
	$('a.uni').mousedown(function(event) {
		switch (event.which) {
			case 1:
				$('a.active').removeClass('active');
				$(this).addClass('active');
				break;
			case 2:
				//alert('Middle mouse button pressed');
				break;
			case 3:
                var id = $(this).attr('data-unit');
				$('div.second-menu').remove();
				$(this).parent().append('<div class="second-menu"><ul><li><a id="expand" href="#">Expandir árbol</a></li><li><a id="collapse" href="#">Contraer árbol</a></li><hr><?php if(getPermisoMenu($_SESSION["profile"], "unidad_editar")) echo "<li><a href=\"index.html.php?task=edit-unit&id='+id+'\" class=\"modal\">Editar unidad</a></li>"; ?><li><a href="index.html.php?task=move-unit&id='+id+'" class="modal">Mover unidad</a></li><li><a class="mata" href="#" onclick="singleUnit('+id+', 0, 0)">Ver unidad</a></li><li><a class="mata" href="#" onclick="singleUnit('+id+', 2, 1);">Fijar unidad</a></li><?php if(getPermisoMenu($_SESSION["profile"], "unidad_eliminar")) echo "<li><a href=\"#\" onclick=\"deleteUnit('+id+')\">Eliminar unidad</a></li>"; ?><li disabled><a href=\"#\" onclick=\"actualizarUnit('+id+')\">Actualizar</a></li><li><a id="all-units" href="#">Mostrar todas las unidades</a></li></ul></div>');
				$('div.second-menu').css({'left': event.pageX - 2, 'top': event.pageY - 2});
				break;
				return false;
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
	
	//INICIO: tabs panel de eventos
	$('#events').show();
	$('a.tab').click(function(){
		var tab = $(this).attr('href');
		$('.tab').parent().removeClass('active');
		$(this).parent().addClass('active');
		$('.tabs').hide();
		$(tab).show();
		return false;
	});
	$('.up-down').click(function(){
		$('#tabs-container').slideToggle('fast');
		$(this).toggleClass('up');
		return false;
	});
	//FIN: tabs panel de eventos

});

//INICIO: Mapa principal con geocercas y marcadores de unidades

var map, myLatlng, x;
var markers = [];
var interval;
var interval_single;
var flag_center;
var intervalPanel;
var intervalMapa;
var folder;
var single;
//google.maps.visualRefresh = true;
function initialize() {
	map = new google.maps.Map(document.getElementById('map-canvas'), {
		zoom: 14,
		center: new google.maps.LatLng(-38.740368, -72.596275),
		//mapTypeControl: false,
		streetViewControl: false,
		panControl: false,
	panControlOptions: {
		position: google.maps.ControlPosition.TOP_RIGHT
	},
	zoomControl: true,
	zoomControlOptions: {
		style: google.maps.ZoomControlStyle.SMALL,
		position: google.maps.ControlPosition.TOP_LEFT
	},
	scaleControl: true,
	scaleControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT
    },
	mapTypeId: google.maps.MapTypeId.HYBRID
	});
	<?php
		$q_geofence="SELECT * FROM vigia.geofences";
		
		$geofence=$db->query($q_geofence);
		while ($q_geofence = pg_fetch_object($geofence)){
			imprime_geofence($q_geofence->id_geofence,$q_geofence->fk_id_tipo,$cont);
		$cont++;
		}
	?>
	
	allUnits();
	interval =	setInterval('allUnits()', 10000); 
}

$('#all-units').livequery('click', function(event){
	clearInterval(interval);
	clearInterval(interval_single);
	clearInterval(intervalPanel);
	$('#dialog').hide();
	interval =	setInterval('allUnits()', 10000);
	allUnits();
});

function allUnits(){
	$.getJSON(site_path+'home/markers.php', function(data){
		$.each(data, function(j,m){
			clearMarkers();
		});
		$.each(data, function(i,item){
			if(item.status == 'ONLINE'){
				if(item.motor == 'TRUE'){
					folder = '../images/icons/active/'+item.icono;
				}
				else if(item.motor == 'FALSE'){
					folder = '../images/icons/iddle/'+item.icono;
				}
			}else{
				folder = '../images/icons/inactive/'+item.icono;
			}

			$('a[data-unit='+item.id_unidad+']').css('background', 'url('+folder+') no-repeat left center');
			
			markers[i] = new google.maps.Marker({
				position: new google.maps.LatLng(item.lat, item.lng),
				map: map,
				icon: folder
			});
		});
	}); 
}

//FIN: Mapa principal con geocercas y marcadores de unidades

//INICIO: Mostrar solo unidad seleccionada

function singleUnit(id, flag_center, single){
	clearInterval(interval);
	clearInterval(interval_single);
	clearInterval(intervalPanel);
	setSingleUnit(id, flag_center);
	if(flag_center == 0){
		flag_center = 1;
	}
	if(single == 1){
		allUnits();
		interval =	setInterval('allUnits()', 10000);
	}
	interval_single = setInterval('setSingleUnit('+id+', '+flag_center+')', 10000);
	intervalPanel = setInterval('actualizar_panel('+id+', '+flag_center+', '+single+')', 10000);
}

function setSingleUnit(id, flag_center){
	$.getJSON(site_path+'panel/flotas.php?op=single-unit&id_unidad='+id, function(data){
		clearMarkers();
		var newLatlng = new google.maps.LatLng(data.lat, data.lng);
		if(data.status == 'ONLINE'){
				if(data.motor == 'TRUE'){
					folder = '../images/icons/active/'+data.icono;
				}
				else if(data.motor == 'FALSE'){
					folder = '../images/icons/iddle/'+data.icono;
				}
			}else{
				folder = '../images/icons/inactive/'+data.icono;
			}
		
		$('a[data-unit='+data.id_unidad+']').css('background', 'url('+folder+') no-repeat left center');
		
		markers[0] = new google.maps.Marker({
			position: newLatlng,
			map: map,
			icon: folder
		});
		if(flag_center == 0){
			centerUnit(newLatlng, flag_center);
			flag_center = 1;
		}else if(flag_center == 2){
			centerUnit(newLatlng, flag_center);
		}
	});
}

function centerUnit(newLatlng, flag_center){
	if(flag_center == 0){
		map.setCenter(newLatlng);
		map.panTo(newLatlng);
	}else if(flag_center == 2){
		map.setCenter(newLatlng);
		map.panTo(newLatlng);
	}
}

function clearMarkers() {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
}

//FIN: Mostrar solo unidad seleccionada

//INICIO: Llama a las funciones para mostrar la informacion de l cuadro de dialogo en el mapa y la info del panel

function informacion(id, flag_center){
	clearInterval(intervalPanel);
	clearInterval(interval);
	clearInterval(interval_single);
	
	actualizar_panel (id, flag_center);
	
	if(flag_center == 0){
		flag_center = 1;
	}
	interval =	setInterval('allUnits()', 10000);
	intervalPanel = setInterval('actualizar_panel('+id+','+flag_center+')', 10000);
}
function diferencia(a,b){
		if(a>b)
			return a-b;
		else
			if(a<b)
			return b-a;
			else
				return 0;
	}
function tiempo_estado_motor(id){
$.getJSON(site_path+'panel/flotas.php?op=2&id_unidad='+id, function (data){
console.log(fecha); 
		var fecha = new Date();
		var hora = fecha.getHours() 
		var minuto = fecha.getMinutes() 
		var segundo = fecha.getSeconds()

		var fecha_hora = data.time_status.split(' ');

		var hora_ = fecha_hora[1]; 
		
		var hora_split = hora_.split(':');
		var hora_unidad = Number(hora_split[0]);
		var min_unidad = Number(hora_split[1]);
		var seg_unidad_split = hora_split[2].split('.');
		var seg_unidad = seg_unidad_split[0];
		
		var new_hora=diferencia(hora,hora_unidad);
		var new_minutos=diferencia(minuto,min_unidad);
		var new_segundo=diferencia(segundo,seg_unidad);
setInterval( function difhoras(){

		var fecha2 = new Date();
		var horas = fecha2.getHours()+new_hora; 
		//var minutos = fecha2.getMinutes()+new_minutos; 
		var segundos = fecha2.getSeconds();
			if(segundos == 0)
				new_minutos++;
	if(data.motor == 'TRUE'){
		
		//jQuery('#estado_mapa').epiclock({mode: EC_COUNTDOWN, format: 'V{<sup>dias</sup>} x{<sup>horas</sup>} i{<sup>minutos</sup>} s{<sup>segundos</sup>}', target: 'September 8, 2013 00:00:00'}).clocks(EC_RUN); 	
		$('#estado_mapa').html("En Movimiento : "+horas+':'+new_minutos+':'+segundos);
		}else{
		//jQuery('#estado_mapa').epiclock({mode: EC_COUNTDOWN, format: 'V{<sup>dias</sup>} x{<sup>horas</sup>} i{<sup>minutos</sup>} s{<sup>segundos</sup>}', target: 'September 8, 2013 00:00:00'}).clocks(EC_RUN); 	
		$('#estado_mapa').html("Apagado : "+horas+':'+new_minutos+':'+segundos);
		}		
}
,1000);


});

}
function actualizar_panel (id, flag_center, single){

  var geocoder;
  $.getJSON(site_path+'panel/flotas.php?op=2&id_unidad='+id, function (data){
	$('#odometro').html(data.odometro+' <small>Km/h</small>');
	$('#motor').html(data.motor);
	$('#satelite').html(data.satelite);
	$('#voltaje').html(data.voltaje*0.1+' V');
	$('#btreserva').html(data.voltajebateria*0.1+' V');
	$('#diesel').html(data.diesel+' L');
	$('#velocidad, #rpm').html(data.velocidad+' RPM');
	$('#speed').html(data.velocidad+' <small>Km/h</small>');
	var horamapa = Number(data.timer);
	horamapa = horamapa+6;
	if (horamapa > 18)
		horamapa = 24 - horamapa;
	$('#hora').html(horamapa);
	$('#calidad').html(data.calidad_gsm+' %');
	
	
		var lat= data.lat/1000000;
		var lon= data.lng/1000000;
		
		var centerLatlng = new google.maps.LatLng(lat, lon);
		centerUnit(centerLatlng, flag_center);

		if(single == 1)	allUnits();	
		
		if(data.motor == 'TRUE'){ 
			$('#led-status').css('background', 'url(../images/icons/active/icon_estado.png) no-repeat');
		}
		else if(data.motor == 'FALSE'){
			$('#led-status').css('background', 'url(../images/icons/iddle/icon_estado.png) no-repeat');
		}
		
		if(data.sensor == 'TRUE'){
			$('#led-sensor').css('background', 'url(../images/icons/active/icon_estado.png) no-repeat');
		}
		else if(data.sensor == 'FALSE'){
			$('#led-sensor').css('background', 'url(../images/icons/inactive/icon_estado.png) no-repeat');
		}
		
		geocoder = new google.maps.Geocoder();
		
		var latlng = new google.maps.LatLng(lat, lon);
		geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			mc_read(results, lat, lon);
		} else {
		
		}
	});
	
	$('#odometro_mapa').html(''+data.odometro+' km');
	$('#satelite_mapa').html(data.satelite);
	$('#gsm_mapa').html(data.calidad_gsm);
	var anio = data.gpstime.substring(0, 4);
	var mes = data.gpstime.substring(4, 6);
	var dia = data.gpstime.substring(6, 8);
	var hora = data.gpstime.substring(8, 10);
	var horaint = Number(hora);
	horaint = horaint - 4;
	if(horaint<0)
		horaint = horaint + 24;
	var min = data.gpstime.substring(10, 12);
	var seg = data.gpstime.substring(12, 14);
	$('#gpstime_mapa').html(dia+'-'+mes+'-'+anio+' '+horaint+':'+min+':'+seg);
	$('#grado_mapa').html(data.grado);
	$('#patente_mapa').html(data.patente);
	$('#descripcion_mapa').html(data.des_unidad);
	$('#obs_mapa').html(data.obs_unidad);
	$('#lat').html(data.lat/1000000);
	$('#lng').html(data.lng/1000000);
	$('#voltaje_mapa').html(''+data.voltaje*0.1 +' v');
	$('#btreserva_mapa').html(''+data.voltajebateria*0.1 +' v');
	$('#diesel_mapa').html(data.diesel);
	$('#velocidad_mapa').html(''+data.velocidad+' Km/h');
	
	var horamapa = data.timer.substring(0, 2);
	var minutomapa = data.timer.substring(3, 5);
	var segundomapa = data.timer.substring(6, 8);
	var horamapa = Number(horamapa);
	horamapa = horamapa+6;
	if (horamapa > 18)
		horamapa = 24 - horamapa;
	$('#hora_mapa').html(horamapa+':'+minutomapa+':'+segundomapa);
	
		
		/*
		if(data.motor == 'TRUE'){
		jQuery('#estado_mapa').epiclock({mode: EC_COUNTDOWN, format: 'V{<sup>dias</sup>} x{<sup>horas</sup>} i{<sup>minutos</sup>} s{<sup>segundos</sup>}', target: 'September 8, 2013 00:00:00'}).clocks(EC_RUN); 	
		//$('#estado_mapa').html("En Movimiento : "+horas+':'+minutos+':'+segundos);
		}else{
		jQuery('#estado_mapa').epiclock({mode: EC_COUNTDOWN, format: 'V{<sup>dias</sup>} x{<sup>horas</sup>} i{<sup>minutos</sup>} s{<sup>segundos</sup>}', target: 'September 8, 2013 00:00:00'}).clocks(EC_RUN); 	
		//$('#estado_mapa').html("Apagado : "+horas+':'+minutos+':'+segundos);
		}*/
		/*
	setInterval(function reloj_mapa(){ 
		
		var fecha2 = new Date();
		var horas = fecha2.getHours()+new_hora; 
		//var minutos = fecha2.getMinutes()+new_minutos; 
		var segundos = fecha2.getSeconds();
			if(segundos == 0)
				new_minutos++;

		//console.log(hora_unidad);
		
	
	}, 500);*/
	

				
	//velocidad
	if(data.velocidad){
	  var d = get_speed_degrees(data.velocidad);
	  $('#aguja_velocidad').css({"transform": d});
	}
	else{
	  $('#aguja1').css({"transform": "rotate(-149deg)"});
	}
	
	//fuel_level
/*	if(data.diesel <= 15){
	  var d = get_fuel_degrees(data.diesel);
	  $('#aguja2').css({"transform": d});
	}
	else{
	  var d = get_fuel_degrees(data.diesel);
	  $('#aguja_estanque').css({"transform": d});
	}
*/	
	//Voltaje externo
/*	if(data.voltaje){
	  var d = data.voltaje*0.1;
	  $('#aguja_externo').css({"transform": "rotate(" + d.toString() + "deg)"});
	}
	else{
	  $('#aguja_externo').css({"transform": "rotate(-70deg)"});
	}
*/	/*
	//Voltaje de reserva
	if(data.voltajebateria){
	  var d = data.voltajebateria*0.1;
	  for(var i= -70; i=d; i++){
		$('#aguja_reserva').css({"transform": "rotate(" + i.toString() + "deg)"});
	  }
	}
	else{
	  $('#aguja_reserva').css({"transform": "rotate(-70deg)"});
	}
	*/
	//RPM
	if(data.rpm){
	  var d = data.rpm;
	  $('#aguja_rpm').css({"transform": "rotate(" + d.toString() + "deg)"});
	}
	else{
	  $('#aguja_rpm').css({"transform": "rotate(-149deg)"});
	}

	
//funcion que determina el anguro a girar la aguja de la velocidad
	function get_speed_degrees(speed){
	  var degree = (parseFloat(speed)*22)/27 - 149;
	  var degree_string = "rotate(" + degree.toString() + "deg)";
	  return degree_string;
	}
	//funcion que determina el angulo a girar la aguja del combustible
	function get_fuel_degrees(fuel_level){
	  var degree = (parseFloat(fuel_level)*22)/27 - 149;
	  var degree_string = "rotate(" + degree.toString() + "deg)";
	  return degree_string;
	}

  });
}
    
function llenado_panel(id) {
  var unidad = id;
  $.getJSON(site_path+"panel/flotas.php?op=5&id_unidad="+unidad+"", function (data) {
		if(data.id_unidad != ''){
			$("#contenedor").show();
		}
		if ($("#summary").hasClass('summary-active') == true){
			$("#dialog").hide();
		}
		if ($("#details").hasClass('details-active') == true){
			$("#dialog").hide();
		}
		if ($("#settings").hasClass('settings-active') == true){
			$("#dialog").hide();
		}
		if(data.p_tiempo == 1){$("#p_tiempo").show();}else{$("#p_tiempo").hide();}
		if(data.p_estado == 1){$("#p_estado").show();}else{$("#p_estado").hide();}
		if(data.p_num_plato == 1){$("#p_num_plato").show();}else{$("#p_num_plato").hide();}
		if(data.p_velocidad == 1){$("#p_velocidad").show();}else{$("#p_velocidad").hide();}
		if(data.p_direccion == 1){$("#p_direccion").show();}else{$("#p_direccion").hide();}
		if(data.p_latlng == 1){$("#p_lat").show(); $("#p_lng").show();}else{$("#p_lat").hide(); $("#p_lng").hide();}
		if(data.p_grado == 1){$("#p_grado").show();}else{$("#p_grado").hide();}
		if(data.p_volt_externo == 1){$("#p_volt_externo").show();}else{$("#p_volt_externo").hide();}
		if(data.p_volt_respaldo == 1){$("#p_volt_respaldo").show();}else{$("#p_volt_respaldo").hide();}
		if(data.p_distancia == 1){$("#p_distancia").show();}else{$("#p_distancia").hide();}
		if(data.p_horas_motor == 1){$("#p_horas_motor").show();}else{$("#p_horas_motor").hide();}
		if(data.p_alarma == 1){$("#p_alarma").show();}else{$("#p_alarma").hide();}
		if(data.p_tipo_viaje == 1){$("#p_tipo_viaje").show();}else{$("#p_tipo_viaje").hide();}
		if(data.p_tiempo_gps == 1){$("#p_tiempo_gps").show();}else{$("#p_tiempo_gps").hide();}
		if(data.p_satelites == 1){$("#p_satelites").show();}else{$("#p_satelites").hide();}
		if(data.p_hdop == 1){$("#p_hdop").show();}else{$("#p_hdop").hide();}
		if(data.p_gsm == 1){$("#p_gsm").show();}else{$("#p_gsm").hide();}
		if(data.p_obs_unidad == 1){$("#p_obs_unidad").show();}else{$("#p_obs_unidad").hide();}
		if(data.p_des_unidad == 1){$("#p_des_unidad").show();}else{$("#p_des_unidad").hide();}
	});
}

//FIN: Llama a las funciones para mostrar la informacion de l cuadro de dialogo en el mapa y la info del panel

function inhabilitar(){
    return false
}
document.oncontextmenu=inhabilitar

$(document).on("click", "#summary", function(){
    $("#summary").addClass('summary-active');
    $("#map").removeClass('map-active');
    $("#map_hide").removeClass('map-active');
    $("#details").removeClass('details-active');
    $("#settings").removeClass('settings-active');
    $("#map-canvas, #details1, #settings1, #dialog, #panel-events, #all-units").hide();
    $("#summary1").show();
    return false;
});

$(document).on("click", "#map", function(){
    $("#map").addClass('map-active');
    $("#summary").removeClass('summary-active');
    $("#details").removeClass('details-active');
    $("#settings").removeClass('settings-active');
    $("#details1, #settings1, #summary1").hide();
    $("#map-canvas, #panel-events, #all-units").show();
    return false;
});
$(document).on("click", "#map_hide", function(){
    $("#map_hide").addClass('map-active');
    $("#summary").removeClass('summary-active');
    $("#details").removeClass('details-active');
    $("#settings").removeClass('settings-active');
    $("#details1, #settings1, #summary1").hide();
    $("#map-canvas, #dialog, #panel-events, #all-units").show();
    return false;
});


$(document).on("click", ".uni", function(){
	$("#map_hide").addClass('map-active');
	$("#map").hide();
	$("#map_hide").show();
    if ($("#summary").hasClass('summary-active') == true){
    		$("#dialog").hide();
    		$("#map").removeClass('map-active');
			$("#map_hide").removeClass('map-active');
    }
    if ($("#details").hasClass('details-active') == true){
    		$("#dialog").hide();
    		$("#map").removeClass('map-active');
			$("#map_hide").removeClass('map-active');

    }
    if ($("#settings").hasClass('settings-active') == true){
    		$("#dialog").hide();
    		$("#map").removeClass('map-active');
			$("#map_hide").removeClass('map-active');

    }

});

$(".meter > span").each(function() {
  $(this)
    .data("origWidth", $(this).width())
    .width(0)
    .animate({
      width: $(this).data("origWidth")
    }, 1200);
});

  var map_data={
  sublocalidad:"",
  localidad:"",
  pais:"",
  latlon:null,
  lat:10,
  lon:-33.45122431111111}


var s_cont={
  localizacion: {
    latitud: "-33.42994",
    longitud: "-70.637174",
    ruta: "Calle Dominica 54",
    localidad: "Region Metropolitana",
    pais: "Chile"
  }}

  function mc_read(results, lat, lon){
  map_data.latlon=results[0].geometry.location;
  map_data.lat=results[0].geometry.location.lat();
  map_data.lon=results[0].geometry.location.lng();
  s_cont.localizacion.ruta=null;
  map_data.sublocalidad=null;
  $.each(results[0].address_components, function(i,v) {

    if ( v.types[0] == "country") {
      map_data.pais=v.long_name;
      s_cont.localizacion.pais=v.long_name;
      $('#pais').html(map_data.pais);
      $('#localidad').html(s_cont.localizacion.pais);
       
    } else if ( v.types[0] == "locality") {
      map_data.sublocalidad=v.long_name;
      $('#sublocalidad').html(map_data.sublocalidad);
        
    } 
    else if ( v.types[0] == "route") {
      s_cont.localizacion.ruta=v.long_name;
      $('#Localizacion').html(s_cont.localizacion.ruta+' '+results[0].address_components[0].short_name);
      } 

      if(map_data.sublocalidad != null){
      $('#loca').html(s_cont.localizacion.ruta+' '+results[0].address_components[0].short_name+', '+map_data.sublocalidad+', '+map_data.pais);
  		}else{
  			$('#loca').html(s_cont.localizacion.ruta+', '+map_data.pais);
  		}
  });
}

//INICIO: boton para expandir dialogo del mapa
$('#dialogexpand').livequery('click', function(event){
	$('.hidedialog').css('display', 'block');
	$('.hrdialog').show();
	$('.algo').attr('id','dialogcollapse');
	$('.algo1').attr('src','../images/icon-li-up.png');
	return false;
});
//FIN: boton para expandir dialogo del mapa

//INICIO: boton para contraer dialogo del mapa
$('#dialogcollapse').livequery('click', function(event){
	$('.hidedialog').css('display', 'none');
	$('.hrdialog').hide();
	$('.algo').attr('id','dialogexpand');
	$('.algo1').attr('src','../images/icon-li-less.png');
	return false;
});
//FIN: boton para contraer dialogo del mapa

function deleteCompany(id){
	if(!confirm('Esta seguro que desea eliminar esta COMPAÑIA?')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-company', id: id}, function(data){
			alert(data.text);
			location.reload();
		}, "json");
		return false;
	} 
}

function deleteFleet(id){
	if(!confirm('Esta seguro que desea eliminar esta FLOTA?\n\n ¡Esta accion elimanar solo las unidades que pertenecen a esta flota!')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-fleet', id: id}, function(data){
			//alert(data.type+'\n\n'+data.text);
			location.reload();
		}, "json");
		return false;
	} 
}

function deleteSubfleet(id){
	if(!confirm('Esta seguro que desea eliminar esta SUBFLOTA?')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-subfleet', id: id}, function(data){
			//alert(data.type+'\n\n'+data.text);
			location.reload();
		}, "json");
		return false;
	} 
}

function deleteUnit(id){
	if(!confirm('Esta seguro que desea eliminar esta UNIDAD?')) {return false;} 
	
	else {
		$.post('index.code.php', {task: 'delete-unit', id: id}, function(data){
			//alert(data.type+'\n\n'+data.text);
			location.reload();
		}, "json");
		return false;
	} 
}

function actualizarUnit(id){
		//setTimeout("location.href='http://www.vighiaprime.com/home/index.php#'",3000);
		$.post('index.code.php', {task: 'actualizar-unit', id: id}, function(data){
				//alert(data.type+'\n\n'+data.text);
				//location.reload();
		}, "json");
		return false;

}


</script> 
</head>
<body class="heigth" onLoad="initialize()">

<header>
    <img class="logo" src="../images/logo-home.png" width="162" height="44" alt="Logo Vighia">
    <nav>
        <ul>
            <li class="active"><a id="fleets" class="fleet-active" href="<?=SITE_PATH?>/home">FLOTAS</a><span></span></li>
            <li><a id="fences" href="<?=SITE_PATH?>/geofences">GEOCERCAS</a></li>
            <li><a id="users" href="<?=SITE_PATH?>/users">USUARIOS</a></li>
        </ul>
    </nav>
    <a class="logout" href="<?=SITE_PATH?>/fin_sesion.php">Salir</a>
    <div class="clear"></div>
</header>

<section class="main">
	<aside>
    	<div class="clear20"></div>
	
<?php
$cont = 1;
if($_SESSION["profile"] == 1): echo' 
<ul class="master" id="navigation">
    <li><a class="principal less" data-class="algo" href="http://www.google.cl">VIGHIA S.A.</a>';
	endif;
		 while($c = pg_fetch_object($sql)):?>
        <ul <?php if($_SESSION["profile"] != 1) echo 'id="navigation"'; ?> class="company">
            <li class="border"><a class="com" data-company="<?=$c->id_compania?>" href="#<?=$cont?>"><?=$c->compania?></a>
                <?php $sql2 = getFleet($c->id_compania, $p->id_flotas);
                while($f = pg_fetch_object($sql2)):?>
                <ul class="fleet hide">
                    <li><a class="menu icon-more" data-fleet="<?=$f->id_flota?>" href="#<?=$cont?>"><?=$f->nombre_flota?></a>
                        <?php $sql3 = getSubFleet($f->id_flota, $p->id_subflotas);
                        while ($sf = pg_fetch_object($sql3)):?>
                        <ul class="sub-fleet hide">
                            <li><a class="submenu icon-more" data-subfleet="<?=$sf->id_flota?>" href="#<?=$cont?>"><?=$sf->nombre_flota?></a>
                                <?php $sql5 = getUnitFleet($sf->id_flota, $p->id_unidad);
                                while ($un1 = pg_fetch_object($sql5)):?>
                                <ul class="sub-unit hide">
                                    <li><a class="uni" data-unit="<?=$un1->id_unidad?>" href="#<?=$cont?>" onClick="tiempo_estado_motor(<? echo $un1->id_unidad; ?>); informacion(<? echo $un1->id_unidad; ?>); dialog_map(<? echo $un1->id_unidad; ?>); llenado_panel(<? echo $un1->id_unidad; ?>);"><?=$un1->nombre?></a></li>
                                </ul>
                                <?php endwhile; ?>
                            </li>
                        </ul>
                        <?php endwhile; ?>
                        <ul class="unit hide">
                            <?php $sql4 = getUnitFleet($f->id_flota, $p->id_unidad);
                            while ($un2 = pg_fetch_object($sql4)):?>
                            <li><a class="uni" data-unit="<?=$un2->id_unidad?>" href="#<?=$cont?>" onClick="tiempo_estado_motor(<? echo $un2->id_unidad; ?>); informacion(<? echo $un2->id_unidad; ?>, 0); dialog_map(<? echo $un2->id_unidad; ?>); llenado_panel(<? echo $un2->id_unidad; ?>);"><?=$un2->nombre?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                </ul>
                <?php endwhile; ?>
            </li>
            <hr class="blue" />
        </ul>
        <?php endwhile;
 if($_SESSION["profile"] == 1): echo' 
    </li>
</ul>';
endif;?>
        
    </aside>
    <section class="wrapper">
    	<nav>
        	<ul>
            	<li><a id="map" class="map-active" href="#map">Mapa</a></li>
            	<li><a id="map_hide" style="display:none" class="map-active" href="#map">Mapa</a></li>
                <li><a id="summary" href="#summary">Panel</a></li>
                <li><a id="details" href="#details">Detalles</a></li>
                <li><a id="settings" href="#settings">Config</a></li>
                <div class="clear"></div>
            </ul>
        </nav>
        <!-- Mapa donde se muestran las geocercas y unidades geolocalizadas -->
        <!--a id="all-units" href="#">Unidades</a-->
        <div id="map-canvas"></div>
        
       <script type="text/javascript">
        	function dialog_map(id) {
				  var unidad = id;

				        $('#odometro_mapa').html('');
		                $('#satelite_mapa').html('');
		                $('#gsm_mapa').html('');
		                $('#gpstime_mapa').html('');
		                $('#grado_mapa').html('');
		                $('#patente_mapa').html('');
		                $('#descripcion_mapa').html('');
		                $('#obs_mapa').html('');
		                $('#lat').html('');
		                $('#lng').html('');
		                $('#voltaje_mapa').html('');
		                $('#btreserva_mapa').html('');
		                $('#diesel_mapa').html('');
		                $('#velocidad_mapa').html('');
		                $('#estado_mapa').html('');
		                $('#hora_mapa').html('');

		                $('#Localizacion').html('');
		                $('#sublocalidad').html('');
		                $('#pais').html('');
		                $('#nombre').html('');

		                $('#loca').html('');

		                $('#odometro').html('');
		                $('#satelite').html('');
		                $('#voltaje').html('');
		                $('#btreserva').html('');
		                $('#diesel').html('');
		                $('#velocidad').html('');
		                $('#velocidad1').html('');
		                $('#voltajeprogres').attr('style','width:0px;');
		                $('#btreservaprogres').attr('style','width:0px;');
		                $('#dieselprogres').attr('style','width:59px; height:0px;');

				  //console.log('unidad : '+unidad);
				      $.getJSON(site_path+"panel/flotas.php?op=3&id_unidad="+unidad+"", function (data) {
				      	  	if(data.id_unidad != ''){$("#dialog").show();}else{$("#dialog").hide();}
				      	  	if ($("#summary").hasClass('summary-active') == true){
						    		$("#dialog").hide();
						    }
						    if ($("#details").hasClass('details-active') == true){
						    		$("#dialog").hide();
						    }
						    if ($("#settings").hasClass('settings-active') == true){
						    		$("#dialog").hide();
						    }
				      	  	if(data.m_tiempo == 1){$("#m_tiempo").show();}else{$("#m_tiempo").hide();}
				      	  	if(data.m_estado == 1){$("#m_estado").show();}else{$("#m_estado").hide();}
				      	  	if(data.m_num_plato == 1){$("#m_num_plato").show();}else{$("#m_num_plato").hide();}
				      	  	if(data.m_velocidad == 1){$("#m_velocidad").show();}else{$("#m_velocidad").hide();}
				      	  	if(data.m_direccion == 1){$("#m_direccion").show();$("#m_pais").show();$("#m_localidad").show();}else{$("#m_direccion").hide();$("#m_pais").hide();$("#m_localidad").hide();}
				      	  	if(data.m_latlng == 1){$("#m_lat").show(); $("#m_lng").show();}else{$("#m_lat").hide(); $("#m_lng").hide();}
				      	  	if(data.m_grado == 1){$("#m_grado").show();}else{$("#m_grado").hide();}
				      	  	if(data.m_volt_externo == 1){$("#m_volt_externo").show();}else{$("#m_volt_externo").hide();}
				      	  	if(data.m_volt_respaldo == 1){$("#m_volt_respaldo").show();}else{$("#m_volt_respaldo").hide();}
				      	  	if(data.m_distancia == 1){$("#m_distancia").show();}else{$("#m_distancia").hide();}
				      	  	if(data.m_horas_motor == 1){$("#m_horas_motor").show();}else{$("#m_horas_motor").hide();}
				      	  	if(data.m_alarma == 1){$("#m_alarma").show();}else{$("#m_alarma").hide();}
				      	  	if(data.m_tipo_viaje == 1){$("#m_tipo_viaje").show();}else{$("#m_tipo_viaje").hide();}
				      	  	if(data.m_tiempo_gps == 1){$("#m_tiempo_gps").show();}else{$("#m_tiempo_gps").hide();}
				      	  	if(data.m_satelites == 1){$("#m_satelites").show();}else{$("#m_satelites").hide();}
				      	  	if(data.m_hdop == 1){$("#m_hdop").show();}else{$("#m_hdop").hide();}
				      	  	if(data.m_gsm == 1){$("#m_gsm").show();}else{$("#m_gsm").hide();}
				      	  	if(data.m_obs_unidad == 1){$("#m_obs_unidad").show();}else{$("#m_obs_unidad").hide();}
				      	  	if(data.m_des_unidad == 1){$("#m_des_unidad").show();}else{$("#m_des_unidad").hide();}
							$('#nombre').html(data.nombre);
				              });
				  }
				function llenadotable(){
				  	$("#resultados").html('');
					  $.getJSON(site_path+"panel/flotas.php?op=unit-table", function (data) {
					      	  	var estado, i=0;
					      	  		$.each(data, function(m,y){ 
					      	  			var anio = y.tiempo.substring(0, 4);
										var mes = y.tiempo.substring(4, 6);
										var dia = y.tiempo.substring(6, 8);
										var hora = y.tiempo.substring(8, 10);
										var min = y.tiempo.substring(10, 12);
										var seg = y.tiempo.substring(12, 14);
					      	  			
						      	  		if(i%2==0){
							      	  		 htm='<tr> <td>'+dia+'-'+mes+'-'+anio+' '+hora+':'+min+':'+seg+'</td> <td>'+y.nombre+'</td>';
		                            		 htm+='<td>'+y.unidad_est+'</td> <td>'+y.patente+'</td> <td>'+y.conductor+'</td>';
		                            		 htm+='<td></td> <td>'+y.nombre_flota+'</td> <td></td>';
		                            		 htm+='<td>'+y.id+'</td> </tr>';
		                            		 i++;
	                            		}else{
							      	  		 htm='<tr class="alt"> <td>'+dia+'-'+mes+'-'+anio+' '+hora+':'+min+':'+seg+'</td> <td>'+y.nombre+'</td>';
		                            		 htm+='<td>'+y.unidad_est+'</td> <td>'+y.patente+'</td> <td>'+y.conductor+'</td>';
		                            		 htm+='<td></td> <td>'+y.nombre_flota+'</td> <td></td>';
		                            		 htm+='<td>'+y.id+'</td> </tr>';
		                            		 i++;
	                            		}
                            		 $("#resultados").append(htm);
						             }); 
					              });
					}

        </script>

        <div id="panel-events">
        	<ul>
            	<li class="active"><a class="tab" href="#events">Eventos</a></li>
                <li><a class="tab" href="#units" onClick="llenadotable();">Unidades</a></li>
                <li class="last"><a class="up-down up" href="#">Up-down</a></li>
                <div class="clear"></div>
            </ul>
            <div id="tabs-container" style="display:none">
                <div class="tabs hidden" id="events" style="overflow:scroll;height:98px">
                <table>
                    <thead>
                        <tr>
                            <th>Tiempo</th>
                            <th>Nombre unidad</th>
                            <th>Evento</th>
                            <th>Valor</th>
                            <th>Ubicación</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr class="alt">
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>
                        <tr>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                            <td>data</td>
                        </tr>

                        
                        </tbody>
                </table>
                </div>
                <div class="tabs hidden" id="units" style="overflow:scroll;height:98px">
                <table>
                    <thead>
                        <tr>
                            <th>Tiempo</th>
                            <th>Nombre unidad</th>
                            <th>Estado</th>
                            <th>Numero patente</th>
                            <th>Conductor</th>
                            <th>Ubicación</th>
                            <th>Flota</th>
                            <th>Grupo</th>
                            <th>Id</th>
                        </tr>
                    </thead>
                    
                    <tbody id="resultados">

                        
                        </tbody>
                </table>
                </div>
            </div>
        </div>

	        <div id="dialog">
	        	<table width= "350px" style="margin-left: 11px;" class="hidedialog" border="0" cellpadding="0" cellspacing="0">
	        		
    			<a href="#"><h4 id="dialogcollapse" class="algo" style="color:white; text-align:center; font-size:10px; height:20px; margin-top:10px;"><div id="nombre"></div> <img class="algo1" style="height: 6px;width: 12px;margin-top: -9px;margin-left: 100px;position: absolute;" src="../images/icon-li-up.png"> </h4> </a>
    			<hr class="hrdialog">
	        		<tr id="m_tiempo" style="color:white; font-size:10px;display:none;">
	        			<td style="width:82px">Hora</td>
	        			<td style="width:7px">:</td>
	        			<td style="width:200px;"><div id="hora_mapa"></div></td>
	        		</tr>
	        		<tr id="m_estado" style="color:white; font-size:10px">
	        			<td style="width:60px">Estado</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;" id="estado_mapa"></td>
	        		</tr>
	        		<tr id="m_num_plato" style="color:white; font-size:10px">
	        			<td style="width:60px">Numero Patente</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="patente_mapa"></div></td>
	        		</tr>
	        		<tr id="m_velocidad" style="color:white; font-size:10px">
	        			<td style="width:60px">Velocidad</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="velocidad_mapa" ></div> </td>
	        		</tr>
	        		<tr id="m_direccion" style="color:white; font-size:10px">
	        			<td style="width:60px">Dirección</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="Localizacion"></div></td>
	        		</tr>
	        		<tr id="m_localidad" style="color:white; font-size:10px">
	        			<td style="width:60px">Localidad</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="sublocalidad"></div></td>
	        		</tr>
	        		<tr id="m_pais" style="color:white; font-size:10px">
	        			<td style="width:60px">Pais</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="pais"></div></td>
	        		</tr>
	        		<tr id="m_lat" style="color:white; font-size:10px">
	        			<td style="width:60px">Latitud</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="lat"></div></td>
	        		</tr>
	        		<tr id="m_lng" style="color:white; font-size:10px">
	        			<td style="width:60px">Longitud</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;height: 17px;"><div id="lng"></div></td>
	        			<td style="width:7px;"></td>
	        		</tr>
	        		
	        		<tr id="m_grado" style="color:white; font-size:10px">
	        			<td style="width:60px">Grado</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;height: 17px;"><div id="grado_mapa"></div></td>
	        		</tr>

	        		<tr id="m_volt_externo" style="color:white; font-size:10px">
	        			<td style="width:60px">Voltaje Externo</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="voltaje_mapa"></div></td>
	        		</tr>
	        		<tr id="m_volt_respaldo" style="color:white; font-size:10px">
	        			<td style="width:60px">Voltaje Respaldo</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="btreserva_mapa"></div></td>
	        		</tr>
	        		<tr id="m_distancia" style="color:white; font-size:10px">
	        			<td style="width:60px">Odometro</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="odometro_mapa"></div></td>
	        		</tr>
	        		<tr id="m_horas_motor" style="color:white; font-size:10px">
	        			<td style="width:60px">Horas Motor</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;">---</td>
	        		</tr>
	        		<tr id="m_alarma" style="color:white; font-size:10px">
	        			<td style="width:60px">Alarma</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;">---</td>
	        		</tr>
	        		<tr id="m_tipo_viaje" style="color:white; font-size:10px">
	        			<td style="width:60px">Tipo Viaje</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;">---</td>
	        		</tr>
	        		<tr id="m_tiempo_gps" style="color:white; font-size:10px">
	        			<td style="width:60px">Tiempo Viaje</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="gpstime_mapa"></div></td>
	        		</tr>
	        		<tr id="m_satelites" style="color:white; font-size:10px">
	        			<td style="width:60px">Satelite</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="satelite_mapa"></div></td>
	        		</tr>
	        		<tr id="m_hdop" style="color:white; font-size:10px">
	        			<td style="width:60px">Hdop</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;">---</td>
	        		</tr>
	        		<tr id="m_gsm" style="color:white; font-size:10px">
	        			<td style="width:60px">GSM</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="gsm_mapa"></div></td>
	        		</tr>
	        		<tr id="m_obs_unidad" style="color:white; font-size:10px">
	        			<td style="width:60px">Observacion</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="obs_mapa"></div></td>
	        		</tr>
	        		<tr id="m_des_unidad" style="color:white; font-size:10px">
	        			<td style="width:60px">Descripcion</td>
	        			<td style="width:6px">:</td>
	        			<td style="width:60px;"><div id="descripcion_mapa"></div></td>
	        		</tr>
	        		
	        	</table>
	        </div>
        
        <div id="summary1">
        
            <div id="contenedor" class="hide">
			  
			<!-- Seccion uno -->
			  <div class="secciones">
			<!-- UBICACION -->
            	<div id="p_direccion" class="info">
                	<h6>Ubicación</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="loca" style="font-size:12px; min-width:350px"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div>            
			<!-- FIN UBICACION -->
            <br>
			<!-- ESTADO -->
			    <div id="p_estado">
                    <div class="signal">
                    	<div class="wrap">
                            <span id="led-status"></span>
                            <p>Conectado</p>
                        </div>
                    	<div class="wrap">
                            <span id="led-sensor"></span>
                            <p>Sensor</p>
                        </div>
                        <div class="clear"></div>
                    </div>
			    </div>
			<!--FIN ESTADO -->
			  </div>
			<!-- FIN Seccion uno -->
			  
			<!-- Seccion dos -->
			  <div class="secciones">


			<!-- HORA -->
            	<div id="p_tiempo" class="info">
                	<h6>Hora</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="hora"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div>            
			<!-- FIN HORA -->

			<!-- DISTANCIA -->
              
            	<div id="p_distancia" class="info">
                	<h6>Odometro</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="odometro"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div>            
			<!-- FIN DISTANCIA -->

			<!-- HORAS SERVICIOS -->
            	<!--div class="info">
                	<h6>Horometro</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="orometro"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div-->            
			<!-- FIN HORAS SERVICIOS -->

			<!-- HORAS SATELITE -->
            	<div id="p_satelites" class="info">
                	<h6>Satelites</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="satelite"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div>            
			<!-- FIN SATELITE -->

			<!-- CALIDAD GSM-->
            	<div id="p_satelites" class="info">
                	<h6>Calidad GSM</h6>
                    <div class="block">
                        <div class="bg-left"></div>
                        <div class="bg-center" id="calidad"></div>
                        <div class="bg-right"></div>
                        <div class="clear"></div>
                	</div>
                </div>            
			<!-- FIN CALIDAD GSM -->

			  </div>
			<!-- FIN Seccion dos -->
            
			<!-- Seccion tres -->
			  <div class="secciones">
			<!-- VOLTAJE-->
            	<div id="p_volt_externo" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="volt_externo" src="../images/panel_bg_volexterno.png">
                            <img id="aguja_externo" class="aguja_bateria" src="../images/aguja.png">
                            <p class="value" id="voltaje"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>Voltaje externo</h6>
                	</div>
                </div> 
			<!-- FIN VOLTAJE -->
            
			<!-- BATERIA RESERVA--> 
            	<div id="p_volt_respaldo" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="volt_externo" src="../images/panel_bg_volreserva.png">
                            <img id="aguja_reserva" class="aguja_bateria" src="../images/aguja.png">
                            <p class="value" id="btreserva"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>Voltaje de reserva</h6>
                	</div>
                </div> 
			<!--FIN BATERIA RESERVA-->
            
			<!-- CALIDAD DE GSM 
            	<div id="p_gsm" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="volt_externo" src="../images/panel_bg_senal.png" height="139" width="213">
                            <img id="aguja_reserva" class="aguja_bateria" src="../images/aguja.png">
                            <p class="value" id="calidad"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>Calidad de GSM</h6>
                	</div>
                </div> 
			<FIN CALIDAD DE GSM-->
            
			  </div>
			<!-- FIN Seccion tres-->
			<!-- Seccion cuatro -->
			  <div class="secciones">
            	<div id="p_velocidad" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="velocidad" src="../images/panel_bg_velocidad.png">
                            <img id="aguja_velocidad" class="aguja" src="../images/aguja2.png">
                            <p class="value_watch" id="speed"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>Velocidad</h6>
                	</div>
                </div> 
                
            	<div id="p_rpm" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="velocidad" src="../images/panel_bg_rpm.png">
                            <img id="aguja_rpm" class="aguja" src="../images/aguja2.png">
                            <p class="value_watch" id="rpm"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>RPM (x1000)</h6>
                	</div>
                </div> 
                
            	<div id="p_estanque" class="info instrumento">
                    <div class="block stroke">
                        <div class="reloj">
                            <img class="velocidad" src="../images/panel_bg_estanque.png">
                            <img id="aguja_estanque" class="aguja" src="../images/aguja2.png">
                            <p class="value_watch" id="diesel"></p>
                        </div>
                        <div class="clear"></div>
                        <h6>Combustible</h6>
                	</div>
                </div> 
              
			  </div>
			<!-- FIN Seccion cuetro-->
			</div>
        </div><!-- FIN SUMMARY -->
        <div id="details1" style="height:550px;width:100%;background:rgba(246,246,246);display:none">
            Tab Detalles
        </div>
        <div id="settings1" style="height:550px;width:100%;background:rgba(246,246,246);display:none">
            Tab Configuracion
        </div>
    </section>
    <div class="clear"></div>
</section>

</body>
</html>


