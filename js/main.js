var toggle =true;
var search="#search";
var search_button="#search_button";
var updateWeight="#updateWeight";


function showOptions(){
	if (toggle) {
		$(".options").show();
		$('#more_options').html("Menos Opc&otilde;es");
	}
	else {
		$(".options").hide();
		$('#more_options').html("Mais Opc&otilde;es");
	}
	toggle=	!toggle;
}
	
	
$(function() {
	$(".left_arrow").click(function(){
		$(".left-menu").animate({left:'-370px'},1000);
		$("#map_canvas").animate({"margin-left":'0px'},1000);
	});
	$(".right_arrow").click(function(){
		$(".left-menu").animate({left:'0px'},1000);
		$("#map_canvas").animate({"margin-left":'370px'},1000);
	});
	
	initGoogleMaps();
	$('#more_options').click(function(e){e.preventDefault();showOptions();});
	
	$(search_button).click(function (e) {
		var address = $(search).val();
		if(address=="") {
			alert("Digite um nome de um endereço!");
			return;
		}
		geocode(address,function(location){
			map.setCenter(location);
			reverseGeocode(location,function(address){
				updateDistrict(address);
			});
			searchMarker.setPosition(location);
			updateMarker(searchMarker,'searchbar');
		});
		e.preventDefault();
	});		
	if(loadCity()){
		$("#city_select").val(loadCity());	
	}
	else{
		location.href="#!/"+$("#city_select").val();
	}
	$("#city_select").change(function(e){
		updateUrl($("#city_select").val());
		loadByCity();
	});
	$("#save_params").click(function(e){
	  	e.preventDefault();
	  	originAccountIntention="Params";
	  	trackIntention(originAccountIntention);
		$( "#create_account" ).modal("show");		
	});
	$("#want_account").click(function(){
		if(originAccountIntention!=null)
			trackEmail($("#email_input").val(),originAccountIntention);
		$( "#alert_account" ).modal("show");
		$( "#create_account" ).modal("hide");
	});
	$("#hide_left").click(function(e){
			e.preventDefault();
			$(".left_menu").hide();
			$("#map_canvas").css("left","0px");
			$("#map_canvas").css("margin-right","0px");
			$(".search").css("left","35%");
			$(".previous").css("left","100px");
			google.maps.event.trigger(map, 'resize'); 	
			trackMenu("Hide");
	});
	$("#show_icon").click(function(e){
		e.preventDefault();
		$(".left_menu").show();
		$("#map_canvas").css("left","460px");
		$("#map_canvas").css("margin-right","460px");
		$(".search").css("left","50%");
		$(".previous").css("left","550px");
		google.maps.event.trigger(map, 'resize'); 		
		trackMenu("Show");
	});
	

}); 		