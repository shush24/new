<html>
<head>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key="></script>
<script src="http://babysoftblog.co.cc/shahma/geojs/geo.js?id=1" type="text/javascript" charset="utf-8"></script>

</head>
<body>
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
$conn = mysql_pconnect('localhost', 'vibhor85_telifon', 'telifon')or die(mysql_error());
mysql_select_db('vibhor85_telifon', $conn) or die(mysql_error());
if(isset($_POST['latitude']))
{
	$latitude=$_POST['latitude'];
	$longitude=$_POST['longitude'];
	$b = bound($latitude,$longitude, $distance,$units);	
	$query = "SELECT h_name,h_address,
		h_latitude,h_longitude FROM hotels WHERE
		h_latitude BETWEEN {$b['S']['lat']} AND {$b['N']['lat']} AND
		h_longitude BETWEEN {$b['W']['lon']} AND {$b['E']['lon']}";
	   $result = mysql_query($query)or die(mysql_error());
	
		$locations = array();
		while ($row = mysql_fetch_assoc($result)) {
		
		$dist = distance($latitude,$lonitude, $row["h_latitude"],$row["h_longitude"], $units);
		if ($dist <= $distance) {
			 $locations[] = array("name"     => $row["h_name"],
								  "address"  => $row["h_address"],
								  "distance" => round($dist, 2));
		}
				echo $row["h_name"]."==>".$row["h_address"]."<br>";
	}
	
}
?>
	
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
	 <input id="txtLat" maxlength="20" name="latitude" size="20" type="text"/> </td>
	 <td width="100px">
	 Lon.<input id="txtLon" maxlength="20" name="longitude" size="20" type="text"/></td>
	
	</tr>
	<tr>
	<td colspan="3">
	  
	 <input type="submit" value="Search"/>
	</td>
	</tr>
	</table>
	</form>
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
</html>