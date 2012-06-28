    <script src="http://babysoftblog.co.cc/shahma/geojs/geo.js?id=1" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCvzv5wLrPHEdRMIfbQLc9pIX9zNaaii6Y&sensor=false"></script> 
    <script type="text/javascript"> 
	
     var customIcons = {
        icon: 'http://accomx.com/images/other_h.png', 
        shadow: 'http://accomx.com/images/other_h.png' 
    }; 
    function bindInfoWindow(marker, map, infoWindow, html) { 
      google.maps.event.addListener(marker, 'click', function() { 
        infoWindow.setContent(html); 
        infoWindow.open(map, marker); 
      }); 
    } 
    function downloadUrl(url, callback) { 
      var request = window.ActiveXObject ? 
          new ActiveXObject('Microsoft.XMLHTTP') : 
          new XMLHttpRequest; 
      request.onreadystatechange = function() { 
        if (request.readyState == 4) { 
          request.onreadystatechange = doNothing; 
          callback(request, request.status); 
        } 
      }; 
      request.open('GET', url, false); 
      request.send(null); 
    } 
    function doNothing() {} 
	function getload()
	{
	 var map = new google.maps.Map(document.getElementById("map"), { 
        center: new google.maps.LatLng(26.84943, 80.919724), 
        zoom: 6, 
        mapTypeId: 'roadmap' 
      });
	}
    //]]> 
  </script>
  <?php function online($l1,$l2,$ln1,$ln2)
{?>
<script type="text/javascript">
	alert("vgfhgf");
	 
      var infoWindow = new google.maps.InfoWindow; 
      downloadUrl("map_xml.php?l1=26.7595984716&l2=26.9392615284&ln1=80.8190382107&ln2=81.0204097893", 
function(data) { 
		var xml = data.responseXML; 
		var image = 'http://accomx.com/images/other_h.png';
		var markers = xml.documentElement.getElementsByTagName("marker"); 
		for (var i = 0; i < markers.length; i++) { 
		  var name = markers[i].getAttribute("address"); 
		  var lath = markers[i].getAttribute("lat"); 
		  var lngh = markers[i].getAttribute("lng"); 
		  var point = new google.maps.LatLng( 
			  parseFloat(markers[i].getAttribute("lat")), 
			  parseFloat(markers[i].getAttribute("lng"))); 
		  //var html = '<div>' + name + ' <a href="showinfo.php?lat=' + lath + '&lng=' + lngh + '" target="_blank">Details</a></div>'; 
		  var icon = customIcons["other"] || {}; 
		  var marker = new google.maps.Marker({ 
			map: map, 
			position: point,
			icon: image
		  }); 
		  bindInfoWindow(marker, map, infoWindow, html); 
		} 
	  });</script>
<?php }?>
  <body onLoad="getload();"> 
  <?php
function destination($lat,$lon, $bearing, $distance,$units="mi") {
    $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
    $rLat = deg2rad($lat);
    $rLon = deg2rad($lon);
    $rBearing = deg2rad($bearing);
    $rAngDist = $distance / $radius;

    $rLatB = asin(sin($rLat) * cos($rAngDist) + 
        cos($rLat) * sin($rAngDist) * cos($rBearing));

    $rLonB = $rLon + atan2(sin($rBearing) * sin($rAngDist) * cos($rLat), 
                           cos($rAngDist) - sin($rLat) * sin($rLatB));

    return array("lat" => rad2deg($rLatB), "lon" => rad2deg($rLonB));
}
// calculate bounding box
function bound($lat,$lon, $distance,$units="mi") {
return array("N" => destination($lat,$lon,   0, $distance,$units),
             "E" => destination($lat,$lon,  90, $distance,$units),
             "S" => destination($lat,$lon, 180, $distance,$units),
             "W" => destination($lat,$lon, 270, $distance,$units));
}
function distance($latA,$lonA, $latB,$lonB, $units="mi") {
    $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
    $rLatA = deg2rad($latA);
    $rLatB = deg2rad($latB);
    $rHalfDeltaLat = deg2rad(($latB - $latA) / 2);
    $rHalfDeltaLon = deg2rad(($lonB - $lonA) / 2);

    return 2 * $radius * asin(sqrt(pow(sin($rHalfDeltaLat), 2) +
        cos($rLatA) * cos($rLatB) * pow(sin($rHalfDeltaLon), 2)));
}
if(isset($_POST['latitude']))
{
	$latitude=$_POST['latitude'];
	$longitude=$_POST['longitude'];
	$b = bound($latitude,$longitude, $distance,$units);	
	$l1= $b['S']['lat'];
	$l2=$b['N']['lat'];
	$ln1=$b['W']['lon'];
	$ln2=$b['E']['lon'];
	online('d1','d2','d3','d4');
}
?>
    <div id="map"  style="width: 1000px; height: 580px; margin:auto"></div> 
	<div>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table width="300px">
	<tr>
		<td>Search :</td>
		<td width="100px"><input id="txtDist" maxlength="4" name="distance" size="4" type="text"/></td>
	 	<td width="100px"><select name="units">
	  		<option value="mi">mi</option>
	  		<option value="km">km</option>
	 		</select>
		</td>
	</tr>
	<tr>
		<td>From :<input style="display:none"  checked="checked"  id="coords" name="searchBy" type="radio" value="coords"/></td>
	 <!--<input name="searchBy" type="radio" value="zip"/>ZIP code
	 <input maxlength="10" name="zipCode" size="5" type="text"/><br/>-->
	 <td width="100px">Lat.
	 <input id="txtLat" maxlength="6" name="latitude" size="6" type="text"/> </td>
	 <td width="100px">
	 Lon.<input id="txtLon" maxlength="6" name="longitude" size="6" type="text"/></td>
	
	</tr>
	<tr>
	<td colspan="3">
	  
	 <input type="submit" value="Search" />
	</td>
	</tr>
	</table>
	</form>
	</div>
	<script type="text/javascript">
	(function () {
			
	function success_callback(p)
		{
			document.getElementById("coords").checked = true;
			document.getElementById("txtLat").value = p.coords.latitude;
			document.getElementById("txtLon").value = p.coords.longitude;
		}
		function error_callback(p)
		{
			//alert('error='+p.code);
		}	
		if(geo_position_js.init()){
			geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
		}
	})();
	</script>
  </body> 