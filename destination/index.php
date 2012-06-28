	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCvzv5wLrPHEdRMIfbQLc9pIX9zNaaii6Y&sensor=false"></script> 
 <script src="http://babysoftblog.co.cc/shahma/geojs/geo.js?id=1" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
function getload()
	{
	  var lat=document.getElementById("txtLat").value;
	  var lon=document.getElementById("txtLon").value;
	 var map = new google.maps.Map(document.getElementById("map"), { 
        center: new google.maps.LatLng(lat, lon), 
        zoom: 8, 
        mapTypeId: 'roadmap' 
      });
	}
</script>
<script type="text/javascript"> 
	
     
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
    //]]> 
  </script>
  <script type="text/javascript">
var customIcons = {
        icon: 'http://accomx.com/images/other_h.png', 
        shadow: 'http://accomx.com/images/other_h.png' 
    }; 
 function online()
{
	
	 var lat=document.getElementById("txtLat").value;
	 var lon=document.getElementById("txtLon").value;
	 var dis=document.getElementById("txtDist").value;
	 var unt=document.getElementById("units").value;
	 if(dis=='')
	 {
	 	dis='500';
	 }
	 var map = new google.maps.Map(document.getElementById("map"), { 
        center: new google.maps.LatLng(lat, lon), 
        zoom: 8, 
        mapTypeId: 'roadmap' 
      });
      var infoWindow = new google.maps.InfoWindow; 
      downloadUrl("map_xml.php?l1="+lat+"&l2="+lon+"&dis="+dis+"&unt="+unt, 
function(data) { 
		var xml = data.responseXML; 
		var image = 'http://accomx.com/images/other_h.png';
		var markers = xml.documentElement.getElementsByTagName("marker"); 
		for (var i = 0; i < markers.length; i++) { 
		  var name = markers[i].getAttribute("name");
		  var addr = markers[i].getAttribute("address"); 
		  var lath = markers[i].getAttribute("lat"); 
		  var lngh = markers[i].getAttribute("lng"); 
		  var distance = markers[i].getAttribute("distance"); 
		   
		  var point = new google.maps.LatLng( 
			  parseFloat(markers[i].getAttribute("lat")), 
			  parseFloat(markers[i].getAttribute("lng"))); 
		  var html = '<div>' + name + '<br>'+addr+'<br>'+distance+' '+unt+'</div>'; 
		 
		  var icon = customIcons["self"] || {}; 
		  var marker = new google.maps.Marker({ 
			map: map, 
			position: point,
			icon: image
		  }); 
		  bindInfoWindow(marker, map, infoWindow, html); 
		} 
	  });
 }</script>
<body onLoad="getload();"> 
     
	<div>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<table width="300px">
	<tr>
		<td>Search :</td>
		<td width="100px"><input id="txtDist" maxlength="4" name="distance" size="4" type="text"/></td>
	 	<td width="100px"><select name="units" id="units">
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
	 <input id="txtLat" maxlength="20" name="latitude" size="20" type="text"/> </td>
	 <td width="100px">
	 Lon.<input id="txtLon" maxlength="20" name="longitude" size="20" type="text"/></td>
	
	</tr>
	<tr>
	<td colspan="3">
	  
	 <input type="button" value="Search" onclick="online();"/>
	</td>
	</tr>
	</table>
	</form>
	</div>
	<div id="map"  style="width: 1000px; height: 580px; margin:auto"></div>
	<script type="text/javascript">
	(function () {
			
	function success_callback(p)
		{
			document.getElementById("coords").checked = true;
			document.getElementById("txtLat").value = p.coords.latitude;
			document.getElementById("txtLon").value = p.coords.longitude;
			getload();
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