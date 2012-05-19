<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	var map;
	function initGoogleMaps() {
		var latlng = new google.maps.LatLng(-27.588784,-48.535967);
		var myOptions = {
		  zoom: 15,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	}
	function addPlace(data,i){
		if(i >= data.length) {
			alert('complete');
			return;
		}
		if(data[i].places.length==0) {
			addPlace(data,i+1);
			return;
		}
		$.post("<?=base_url()?>realestates/add",
			{
			'lat' : data[i].location.lat,
			'lng' : data[i].location.lng,
			'code' : data[i].code,
			'address' : data[i].end,
			'thumb' : data[i].thumb,
			'url' : "http://www.brognoli.com.br/imovel/" + data[i].cod,
			'type' : 'rent'
			},
			function(){
				addPlace(data,i+1);
			}
		);
	}	
	$.getJSON("<?=base_url()?>brognoli_places.json", function(data) {
		addPlace(data,2);
	});
});
</script>