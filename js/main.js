var toggle =true;
var search="#search";
var search_button="#search_button";
var updateWeight="#updateWeight";

function showOptions(){
	if (toggle) {
		$(".options").slideDown();
		$('.header').animate({height:500});
		$('#more_options').html("Menos Opc&otilde;es");
	}
	else {
		$(".options").slideUp();
		$('.header').animate({height:255});
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
	$("#want_account").click(function(){
		if(originAccountIntention!=null)
			trackEmail($("#email_input").val(),originAccountIntention);
		$( "#alert_account" ).dialog("open");
		$( "#create_account" ).dialog("close");
	});
}); 		