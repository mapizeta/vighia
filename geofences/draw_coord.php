<?
include "config.php";
$id_unidad = $_GET["id_unidad"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing"></script>
<style>
html, body, #map_canvas { margin: 0; padding: 0; height: 98%; }
</style>
<script>
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
        drawingModes: [google.maps.drawing.OverlayType.POLYLINE, google.maps.drawing.OverlayType.MARKER, google.maps.drawing.OverlayType.POLYGON ]
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
      }

      google.maps.event.addDomListener(window, 'load', initialize);
      google.maps.event.addDomListener(document.getElementById("map_canvas"), 'ready', function() { drawingManager.setMap(map) } );

</script>
</head>
<body>
<form action="ingreso.php" method="post">
<input name="id_unidad" type="hidden" value="<? echo $id_unidad; ?>" />
<textarea hidden id="action" name="action" rows="8" cols="46"></textarea>
NOMBRE:
<input name="nombre" type="text" />
DESCRIPCION 
<input name="descripcion" type="text" id="descripcion" />
<input name="Guardar" type="submit" value="Guardar" />
<input name="Limpiar" type="button" onclick="clearCoord()" value="Limpiar" />
</form>
    <div id="map_canvas"></div>
</body>
</html>
