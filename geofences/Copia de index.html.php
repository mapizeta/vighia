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
					if(response.type == 'error'){
						alert(response.text);
					}else{
						alert(response.text);
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

function createGeofence(){
	global $id, $db;
	?>
    <link rel="stylesheet" type="text/css" href="<?=SITE_PATH?>/css/interior.css" class="cssfx">
	<script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?=SITE_PATH?>/include/scripts/js/cssfix.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing"></script>
    <style>
    html, body, #map_canvas { margin: 0; padding: 0; height: 98%; }
    </style>
    <script>
    $(function(){
		$('body').css('background-color', 'white');
		$('button').css('margin', '0');
		
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
						alert(response.text);
					}else{
						alert(response.text);
						parent.location.reload();
					}
					form.find('button[type=submit]').removeAttr('disabled');
				}
			});
			return false;
		});
				
    });
	
    function clearCoord(){
    document.getElementById("action").value = "";
    }
    var myOptions = {
      center: new google.maps.LatLng(-38.740368, -72.596275),
      zoom: 5,
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
      mapTypeId: google.maps.MapTypeId.SATELLITE
    };
    
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
    
        var map;
    
          function initialize() {
    
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
              alert(event.latLng);
            });
    
    
            drawingManager.setMap(map);
     
            google.maps.event.addDomListener(drawingManager, 'markercomplete', function(marker) {
              document.getElementById("action").value += "Marker;\n";
              document.getElementById("action").value += marker.getPosition() + ";\n";
            });
    
            google.maps.event.addDomListener(drawingManager, 'polylinecomplete', function(line) {
                path = line.getPath();
                document.getElementById("action").value += "Polyline;\n";
                for(var i = 0; i < path.length; i++) {
                  document.getElementById("action").value += path.getAt(i) + ";\n";
                }
            });
    
            google.maps.event.addDomListener(drawingManager, 'polygoncomplete', function(polygon) {
                path = polygon.getPath();
                document.getElementById("action").value += "Polygon;\n";
                for(var i = 0; i < path.length; i++) {
                  document.getElementById("action").value += path.getAt(i) + ';\n';
                }
            });
			
			google.maps.event.addDomListener(drawingManager, 'circlecomplete', function(circle) {
				path = circle.getPath();
				document.getElementById("action").value += "#circle\n";
				for(var i = 0; i < path.length; i++) {
				  document.getElementById("action").value += path.getAt(i) + '\n';
				}
			});
          }
    
          google.maps.event.addDomListener(window, 'load', initialize);
          google.maps.event.addDomListener(document.getElementById("map_canvas"), 'ready', function() { drawingManager.setMap(map) } );
    
    </script>
    <section class="create">
    	<div id="container">
            <div class="top">
                <h2>Crear geocerca</h2>
            </div>
            <div class="content">
                <form id="form" action="index.code.php">
                    <textarea hidden id="action" name="action" rows="8" cols="46"></textarea>
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" />
                    <label for="descripcion">Descripción</label> 
                    <input id="descripcion" name="descripcion" type="text" id="descripcion" />
                    
                    <button style="width: 110px;" type="submit" form="form"> Crear Geocerca</button>
                    <button type="button" style="width: 80px;" onclick="clearCoord()"> Limpiar</button>
                    <input type="hidden" name="id" value="<?=$id?>" />
                    <input type="hidden" name="task" value="save-geofence" />
                </form>
                <div id="map_canvas"></div>
        	</div>
        </div>
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
              alert(response.type+'\n\n'+response.text);
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
              alert(response.type+'\n\n'+response.text);
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

