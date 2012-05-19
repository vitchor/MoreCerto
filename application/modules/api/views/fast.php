<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/cupertino/jquery-ui.css" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
<script type="text/javascript">
function getPlaces(){
	$.getJSON('http://www.morecerto.com.br/api/get_places', function() {
		setTimeout(function(){getPlaces();},200);
	});
}		
function avaliation(){
	$.getJSON('http://www.morecerto.com.br/api/add_avaliations', function() {
		setTimeout(function(){avaliation();},200);
	});
}
function cache(){
	$.getJSON('http://www.morecerto.com.br/api/cacheimages', function() {
		setTimeout(function(){cache();},500);
	});
}		
function geocode(){
	$.getJSON('http://www.morecerto.com.br/api/geocode', function() {
		setTimeout(function(){geocode();},500);
	});
}
function status(){
	$("#data").load("http://www.morecerto.com.br/api/status");
}
function geocodeTrue(){
	$.getJSON('http://www.morecerto.com.br/api/geocode/true', function() {
		setTimeout(function(){geocodeTrue();},500);
	});
}		
</script>
</head>
<body>
<div id="update"></div>
<button onClick="getPlaces()">Places</button>
<button onClick="avaliation()">Avaliations</button>
<button onClick="cache()">cache</button>
<button onClick="geocode()">Geocode</button>
<button onClick="geocodeTrue()">Geocode True</button>
<button onClick="status()">Status</button>
<div id="map_canvas" style="height:500px;"></div>
<div id="data"></div>
</body>
</html>