<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	function addPlace(data,i){
		if(i >= data.length) {
			alert('complete');
			return;
		}
		$.post("<?=base_url()?>places/add",
			{
			'id' : data[i].id,
			'lat' : data[i].geometry.location.Sa,
			'lng' : data[i].geometry.location.Ta,
			'name' : data[i].name,
			'address' : data[i].vicinity,
			'reference' : data[i].reference,
			'icon' : data[i].icon,
			'types' : JSON.stringify(data[i].types)
			},
			function(){
				addPlace(data,i+1);
			}
		);
	}
	$.getJSON("<?=base_url()?>places.json", function(data) {
		addPlace(data,0);
	});
});
</script>