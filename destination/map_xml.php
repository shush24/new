<?php  
$username="vibhor85_telifon";
$password="telifon";
$database="vibhor85_telifon";
//include("../include/config.php");
// Opens a connection to a MySQL server

$connection=mysql_connect (localhost, $username, $password);
if (!$connection) {  die('Not connected : ' . mysql_error());} 

// Set the active MySQL database

$db_selected = mysql_select_db($database, $connection);
mysql_set_charset('utf8',$connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
} 
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
if(isset($_GET['l1']))
{
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 

	$latitude=$_GET['l1'];
	$longitude=$_GET['l2'];
	$dis=$_GET['dis'];
	$unt=$_GET['unt'];
	$b = bound($latitude,$longitude, $dis,$unt);	
	$query = "SELECT h_name,h_address,
		h_latitude,h_longitude FROM hotels WHERE
		h_latitude BETWEEN {$b['S']['lat']} AND {$b['N']['lat']} AND
		h_longitude BETWEEN {$b['W']['lon']} AND {$b['E']['lon']}";
	   $result = mysql_query($query)or die(mysql_error());
	
		$locations = array();
		while ($row = @mysql_fetch_assoc($result)){  
		  // ADD TO XML DOCUMENT NODE  
		  $dist = distance($latitude,$longitude, $row["h_latitude"],$row["h_longitude"], $unt);
		  if ($dist <= $dis) {
		  $node = $dom->createElement("marker");  
		  $newnode = $parnode->appendChild($node);   
		  $newnode->setAttribute("name",  $row['h_name']);  
		  $newnode->setAttribute("address",  $row['h_address']);  
		  $newnode->setAttribute("lat",  $row['h_latitude']);  
		  $newnode->setAttribute("lng", $row['h_longitude']); 
		  $newnode->setAttribute("distance", round($dist, 2)); 
		  //$newnode->setAttribute("id", $row['type_in_id']); 
		  if(!empty($row['h_address'])) 
		  {
		   $newnode->setAttribute("type", "self");   
		  }
		  else
		  {
		   $newnode->setAttribute("type", "other");   
		  }
		  }
		} 
			// Start XML file, create parent node

			header("Content-type: text/xml"); 

			echo $dom->saveXML();
}

?>