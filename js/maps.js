var map;
var geocoder;
var destinationIcon = base_url+"img/downloadicon.png";
var restaurantIcon = "img/restaurant.png";
var currentInfoWindow;
var MARKER_ZOOM_EXTENT = 14;
var DEFAULT_RADIUS = 3500;
var markers = new Array();
var types = new Array();
var defaultRates=new Array(30,50,40,15,30,10,25,70);
var service;
var currentMarker;
var showedRealEstate = false;
var searchMarker = null;
var currentAddress=null;
var dragTimer = null;
var doSearchTimeout = 500;
var filter ={"type":"rent","kind":"apt"};

function loadByCity(){
	var city_state = loadCityState();
	$.post(base_url+"realestates/city/"+city_state.state+"/"+city_state.city,function(data){
		if(data == null){
			geocode(city_state.city+","+city_state.state,function(location){
				city_full_name = $('#city_select option[value="'+city_state.state+"/"+city_state.city+'"]').text();
				$.post(base_url+"realestates/add_city",
						{
							"short_name":city_state.city,
							"state":city_state.state,
							"long_name":city_full_name,
							"lat":location.lat(),
							"lng":location.lng()
						}
						,function(data){
						searchMarker.setPosition(location);
						map.setCenter(location);
						updateMarker(searchMarker,'loadbycity');		
				});
			});
		}
		else {
			var position = new google.maps.LatLng(data.lat,data.lng);
			searchMarker.setPosition(position);
			map.setCenter(position);
			updateMarker(searchMarker,'loadbycity');			
		}
	});
}
function updateUrl(city){
	location.href="#!/"+city;
} 
function updateUrlRealEstate(id){
	return;
	location.href="#!/"+id;
} 
function cityConvertName(city){
	return city.toLowerCase().replace(" ","_").latinize();
}
function updateDistrict(address){
	$("#district_wrapper").css("visibility","hidden");
	
	var state ="";
	var city_name="";
	
	for(i=0;i<address.address_components.length;i++){
		if(address.address_components[i].types[0]=="sublocality"){
			$("#district").text(address.address_components[i].short_name);
			$("#district_wrapper").css("visibility","visible");
		}
		if(address.address_components[i].types[0]=="administrative_area_level_1")
			state = address.address_components[i].short_name.toLowerCase();
		
		if(address.address_components[i].types[0]=="locality"){
			city_name=address.address_components[i].long_name;
		}
	}
	if(state=="são paulo")state="sp";
	
	if($("#city_select option:contains("+city_name+","+state.toUpperCase()+")").length>0){
		city = $("#city_select option:contains("+city_name+","+state.toUpperCase()+")");
		$("#city_select").val(city.val());;
		updateUrl(city.val());
	}
	else {
		$("#city_select").append("<option value='"+state+"/"+cityConvertName(city_name)+"'>" + city_name+","+state.toUpperCase()+"</option>");
		$("#city_select").val(state+"/"+cityConvertName(city_name));
		updateUrl(state+"/"+cityConvertName(city_name));
	}

}
function updateQtyRealEstates(qty){
	if(qty ==0)
		text = "Nenhum imóvel";
	else if(qty ==1)
		text = "1 imóvel";
	else if(qty >=1)
		text = qty + " imóveis";
	$("#qty_realestate").text(text);
}
function loadByID(){
	return null;
	if ( location.href.indexOf( "#!/") != -1){
		id= location.href.substr(location.href.indexOf( "#!/")+3);
		if(isNaN(id)) return null;
		else return id;
	}		
	else return null;
}
function loadCity(){
	if ( location.href.indexOf( "#!/") != -1){
		state = location.href.substr(location.href.indexOf( "#!/")+3,2);
		city = location.href.substr(location.href.indexOf( "#!/")+3+3);
		return city+","+state;
	}		
	else return null;
}
function loadCityState(){
	if ( location.href.indexOf( "#!/") != -1){
		state = location.href.substr(location.href.indexOf( "#!/")+3,2);
		city = location.href.substr(location.href.indexOf( "#!/")+3+3);
		return {"city":city,"state":state};
	}		
	else return null;
}

function initGoogleMaps() {
	var latlng = new google.maps.LatLng(-27.588784,-48.535967);
	geocoder = new google.maps.Geocoder();
	var myOptions = {
	  zoom: 15,
	  center: latlng,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	service = new google.maps.places.PlacesService(map);
	initHandlers();	
	initSliders();
	google.maps.event.addListener(map, 'dragend', 
			function() {
				if(dragTimer)clearTimeout(dragTimer);
				setTimeout(function(){
					searchMarker.setPosition(map.getCenter());
					google.maps.event.trigger(searchMarker,'dragend');
					dragTimer=null;
				},doSearchTimeout);
			});
	
	google.maps.event.addListener(map, 'click', 
			function(event) {
				map.setCenter(event.latLng);
				searchMarker.setPosition(event.latLng);
				google.maps.event.trigger(searchMarker,'dragend');
			});
	if(defaultMarkerGeocode==null && loadByID()==null){	
		if(loadCity()==null)
			var defaultMarker='Rua Jerônimo Coelho, 389, Florianópolis - SC, 88010-030, Brasil';
		else var defaultMarker = loadCity();
	
		geocode(defaultMarker,function(location){
			map.setCenter(location);
			reverseGeocode(location,function(address){
				updateDistrict(address);
			});
			var marker = createMarker(location,defaultMarker,DEFAULT_RADIUS);
			marker.setPosition(location);
			searchMarker = marker;
		});
	}
	else{
		if(loadByID()!= null){
			$.post(base_url+"realestates/show/"+loadByID(),function(data){
				idRealEstate =loadByID(); 
				var location = new google.maps.LatLng(data.lat,data.lng);
				map.setCenter(location);
				var marker = createMarker(location,"",DEFAULT_RADIUS);
				searchMarker = marker;
				marker.setPosition(location);
			});
		}
		else{
			var location = new google.maps.LatLng(defaultMarkerGeocode[0],defaultMarkerGeocode[1]);
			map.setCenter(location);
			var marker = createMarker(location,"",DEFAULT_RADIUS);
			searchMarker = marker;
			marker.setPosition(location);
			reverseGeocode(location,function(address){
				updateDistrict(address);
			});
		}
	}
}
function initSliders(){
	var i=0;
	$(".slider").each(function(){
		types.push({type:$(this).attr("id")});
		$("#"+$(this).attr("id")).slider({value:defaultRates[i++],
			change:function(){
				recalculateRealEstate();
				showSaveParams();
				trackParameterChange($(this).attr("id"),$(this).slider("value"));
			}
		});
	});
}
function showSaveParams(){
	$("#save_params").css("display","inline-block");
	$(".end").css("height","37");
}
function initHandlers(){
	google.maps.event.addListener(map, 'rightclick', function(e)
	{
		var clickedLatLng = e.latLng;
		searchMarker.setPosition(clickedLatLng);
		updateMarker(searchMarker,'rightclick');
	});
	var input = document.getElementById('search');
	var autocomplete = new google.maps.places.Autocomplete(input);
	
	autocomplete.bindTo('bounds', map);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
		$(search_button).click();
	});
}

function geocode(address,callback){
	geocoder.geocode({ 'address': address }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) callback(results[0].geometry.location);
		else alert("Geocode was not successful for the following reason: " + status);
	});
}
		
function reverseGeocode(position,callback){
	geocoder.geocode({ 'latLng': position }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0])	callback(results[0]);
			else alert('Error');
		}
	});
}
function updateMarker(marker,origin){
	reverseGeocode(marker.getPosition(),function(address){
		if(origin!=null) 
			trackMarkerUpdate(address,origin);
		currentAddress = address;
		updateDistrict(address);			
		address = address.formatted_address;
		marker.item.find(".name").html(address);
		setVisibleSubmarkers(marker,false);
		marker.submarkers=new Array();
		searchPlaces(marker,marker.getPosition(),marker.radius);
		addRealEstate(marker.getPosition(),marker.radius);
	});
}
function addDragHandler(marker){
	google.maps.event.addListener(marker, 'dragend', function () {
		updateMarker(marker,'dragend');
	});
}
function zoomExtents(place){
	var bounds = new google.maps.LatLngBounds();
	bounds.extend(place);
	map.fitBounds(bounds);
}
function createPlaceMarker(place,icon) {
	return new google.maps.Marker({ map: map,	position: place, icon: restaurantIcon});
}
function createMarker(place,address) {
	var item=$('#search_item_template').clone();
	item.attr("id","");
	item.find(".name").html(address);
	
	if(arguments[2])
		var radius = arguments[2];
	else var radius = DEFAULT_RADIUS;
	
	var marker = new google.maps.Marker({ map: map,	position: place, icon: destinationIcon,draggable: true,item:item,radius:radius});
	item.data("marker",marker);
	markers.push(marker);
	addDragHandler(marker);
	map.setCenter(place);
	map.setZoom(MARKER_ZOOM_EXTENT);
	searchPlaces(marker,place,radius);
	addRealEstate(marker.getPosition(),marker.radius);
	var distanceWidget = new DistanceWidget(marker,radius);
	marker.distanceWidget = distanceWidget;
	
	google.maps.event.addListener(distanceWidget, 'distance_changed', function() {
		var distance = distanceWidget.get('distance');
		marker.radius = distance;
		searchPlaces(marker,marker.getPosition(),distance);
		addRealEstate(marker.getPosition(),distance);
	});
	return marker;
}
function hideAllMarkersRecursive(){
	for(var i=0;i<markers.length;i++){
		markers[i].setVisible(false);
		markers[i].distanceWidget.setVisible(false);
		for(var j=0;j<markers[i].submarkers.length;j++)
			markers[i].submarkers[j].setVisible(false);
	}		
}
function setVisibleSubmarkers(marker,visible)
{
	if(marker.submarkers)
		for(var j=0;j<marker.submarkers.length;j++)
			marker.submarkers[j].setVisible(visible);
}
function showMarker(marker){
	currentMarker=marker;
	hideAllMarkersRecursive();
	marker.setVisible(true);
	marker.distanceWidget.setVisible(true);
	setVisibleSubmarkers(marker,$("#services_cb").is(':checked'));
	map.setCenter(marker.getPosition());
	map.setZoom(MARKER_ZOOM_EXTENT);
}
function removeSubmarkers(marker){
	if(marker.submarkers){
		for(var j=0;j<marker.submarkers.length;j++)
			removeMarker(marker.submarkers[j]);
	}
}
function searchPlaces(marker,place,radius){
	return;
	var request = {location: place, radius: radius,types: getEnabledTypes()};
    service.search(request, function(results, status){
		if (status == google.maps.places.PlacesServiceStatus.OK) {
			var places=new Array();
			removeSubmarkers(marker);
			marker.submarkers = new Array();
			for (var i = 0; i < results.length; i++) {
				marker.submarkers.push(createPlaceMarker(results[i].geometry.location,results[i].icon));
				places.push({location: results[i].geometry.location,types:results[i].types});
			}
			calculateDistances(marker,places,marker.submarkers,radius);
			showMarker(marker);
		}
	});
}
function updateRates(){
	var sum =0;
	$(types).each(function(){
		sum += $("#" + $(this)[0].type).slider("value");		
		$(this)[0].qty=0;
		$(this)[0].sum=0;
	});
	if(sum!=0){
		$(types).each(function(){
			$(this)[0].value=$("#" + $(this)[0].type).slider("value")/sum*100;
		});
	}	
}
function getEnabledTypes(){
	var array = new Array();
	$(types).each(function(){
		if($("#" + $(this)[0].type).slider("value")>0)
			array.push(this.type);	
	});
	return array;
}
function updateTypeIndex(index,data){
	$(types).each(function(){
		if(parseInt(jQuery.inArray($(this)[0].type,data))>=0){			
			$(this)[0].qty++;
			$(this)[0].sum += index*$(this)[0].value;
			return false;
		}
	});
}
function getIndex(){
	var sum =0;
	$(types).each(function(){
		if($(this)[0].qty >0)
			sum += $(this)[0].sum/$(this)[0].qty;
	});
	return sum;
}
function calculateDistances(marker,places,markers,radius) {
		var origin = marker.getPosition();
		var finalValue=0.5;
		var trapezoidalRate =0.3;
		if(places.length==0)
			alert("Nenhum local encontrado nas redondezas!");
		updateRates();
		for (var j = 0; j < places.length; j++)
		{
			var d = distance(origin,places[j].location);
			var value=0;			
			if(d <= radius) {
				if(d/radius<=0.3)
					value =1;
				else value = (1-finalValue*(d-trapezoidalRate*radius)/((1-trapezoidalRate)*radius));
				updateTypeIndex(value,places[j].types);
			}
			else removeMarker(markers[j]);
		}						
		var index= parseInt(getIndex());
		$(marker.item).find(".index").text(index.toFixed(1));
}

function removeMarker(marker){	marker.setMap(null);}

function distance(p1, p2) {
	if (!p1 || !p2) {
	  return 0;
	}
	var R = 6371000; // Radius of the Earth in m
	var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
	var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
	var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
	  Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
	  Math.sin(dLon / 2) * Math.sin(dLon / 2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	var d = R * c;
	return d;
};
function calculateIndex(data){
	var sum=0;
	updateRates();
	$(types).each(function(){
		if(data[$(this)[0].type])
			sum += parseFloat(data[$(this)[0].type])*$(this)[0].value/100;
	});	
	return parseInt(sum);
}
String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1).toLowerCase();
};
function indexToText(index){
	if(index <60) return "Regular";
	else if(index>=60 && index <75) return "Bom";
	else if(index>=75 && index <85) return "Muito Bom";
	else if(index>=85) return "Excelente";
}
function indexIcon(index){
	if(index <60) var icon =  "orange/number_"+index+".png";
	else if(index>=60 && index <75) var icon =  "yellow/number_"+index+".png";
	else if(index>=75 && index <85) var icon =  "green/number_"+index+".png";
	else if(index>=85) var icon =  "blue/number_"+index+".png";
	return base_url + "img/"+icon;
}
function realestateImage(data){
	if(data.cached_image==true)
		return base_url+ "uploads/cache/"+data.idrealestates+".jpg";
	else if(data.thumb!= null && data.thumb!="")
		return data.thumb;
	else return base_url + "img/no_image.png";
}
function createRealEstateMarker(data){
	var place = new google.maps.LatLng(data.lat, data.lng);
	
	var index= calculateIndex(data);
	
	var marker = new google.maps.Marker({ map: map,	position: place, icon: indexIcon(index)});
	
	if(data.district && data.district!=null && data.district != "null")
		var neighborhood = data.district.capitalize();
	else var neighborhood = data.district;
	
	if(data.city)
		var city =  data.city.capitalize();
	else var city =  data.city;

	var state =  data.state;	
	
	var type_kind ="";
	
	if(data.type=="rent")
		type_kind = "Aluguel de ";
	else type_kind = "Compra de ";
	
	if(data.kind == "apt") type_kind+="Apartamento ";
	else if(data.kind == "kit") type_kind+="Kitnet ";
	else if(data.kind == "house") type_kind+="Casa ";
	else if(data.kind == "room") type_kind+="Quarto ";
		
	if(neighborhood != null && neighborhood != "" && neighborhood!= "null"){
		var info_realestate = type_kind + " no bairro "+ neighborhood + " em " + city+ ", " + state;
	}
	else {
		var info_realestate = type_kind +" em " + city+ ", " + state;
	}
	
	if(parseInt(data.price)==0)
		var price = "-"; 
	else var price = "R$ " + parseInt(data.price).toFixed(2);
	
	var content ="<div class='info'>"+
				'<span>'+ info_realestate + "</span>"+
				"<h2>"+ price+"</h2>"+
				'<div class="classification"><div class="cover"></div><div class="star" style="width: ' + index+'%;"></div></div>'+
				"<img src='"+realestateImage(data)+"' onError=\"this.src='"+base_url + "img/no_image.png' \"></img>"+
				"<a href='#' class='favorite'>Salvar como Favorito</a>"+
				"<a target='_blank' class='btn btn-primary more_info' id=\"" + data.idrealestates+"\" href='"+data.url+"'>Veja mais informa&ccedil;&otilde;es</a>"+
				"</div>";	

	//'<iframe class="like_button" src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.morecerto.com.br%2Frealestates%2Fshow%2F'+ data.idrealestates+ '&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35&amp;appId=213643752071992" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>'	
	
	var item = $('<div class="real_state_detail" id="' + data.idrealestates+ '">'+
					'<div class="real_estate">'+		
						'<ul class="thumbnails">'+
							'<li class="span_thumb">'+
								'<a href="#" class="thumbnail">'+
									'<img src="' + realestateImage(data) +  '" onError="this.src=\''+base_url + 'img/no_image.png\' " >'+
								'</a>'+
							'</li>'+
							'<li class="real_estate_info">'+
								'<h4>'+info_realestate+'</h4>'+
								'<span class="price">'+  price + '</span>'+
								'<div class="alert alert-info index_div">'+
									'<div class="classification">'+
										'<div class="cover"></div>'+
										'<div class="star" style="width:' + index + '%;"></div>'+
										'<span class="index">'+index+'</span>'+
									'</div>'+
									'<span class="indexName">'+indexToText(index)+'</span>'+
								'</div>'+
							'</li>'+
						'</ul>'+
					'</div>'+
				  '</div>');
	
	var infowindow = new google.maps.InfoWindow({
		content: content
	});
	
	$(".small").click(function(e){
		e.preventDefault();
		$( "#howitworks" ).modal("show");		
	});

	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	  currentInfoWindow =infowindow;
	  updateUrlRealEstate($(".more_info").attr("id"));
	  $(".more_info").click(function(e){
		    trackRealEstateClick(data.url,data.price,index,'Redirect');
			$.get(base_url + "realestates/click/"+$(this).attr("id"),function(){
			});
		});
	  $(".favorite").click(function(e){
		  	e.preventDefault();
		  	originAccountIntention="Favorite";
		  	trackIntention(originAccountIntention);
			$( "#create_account" ).modal("show");		
		});
	  trackRealEstateClick(data.url,data.price,index,'Map');
	});

	item.hover(function(){
		if(currentInfoWindow) currentInfoWindow.close();
		google.maps.event.trigger(marker,'click');
		$(this).find(".index_div").css("background","#ddd");
		$(this).find(".small").css("visibility","visible");
	},
	function(){
		$(this).find(".index_div").css("background","#eee");
		$(this).find(".small").css("visibility","hidden");
	});
	
	$("#search_results").append(item);	
	
	return marker;
}
$(function(){
	$(".next").click(function(e){e.preventDefault();nextRealState();});
	$(".previous").click(function(e){e.preventDefault();previousRealState();});
});

var realStateMarkers= new Array();
var currentRealState=0;

function nextRealState(){
	if(realStateMarkers.length==0) alert("Nenhum imóvel disponível. Faça uma busca por um endereço.");
	if(currentInfoWindow) currentInfoWindow.close();
	currentRealState++;
	if(currentRealState>=realStateMarkers.length) currentRealState=0;
	$("#search_results").scrollTo("#"+realStateMarkers[currentRealState].data.idrealestates);
	google.maps.event.trigger(realStateMarkers[currentRealState].gmaps,'click');
}
function previousRealState(){
	if(realStateMarkers.length==0) alert("Nenhum imóvel disponível. Faça uma busca por um endereço.");
	if(currentInfoWindow) currentInfoWindow.close();
	currentRealState--;
	if(currentRealState < 0)  currentRealState=realStateMarkers.length;
	$("#search_results").scrollTo("#"+realStateMarkers[currentRealState].data.idrealestates);	
	google.maps.event.trigger(realStateMarkers[currentRealState].gmaps,'click');
}
function toggleRealState(visible){
	if(visible)
		for(var j=0;j<realStateMarkers.length;j++)
			realStateMarkers[j].gmaps.setMap(map);
	else for(var j=0;j<realStateMarkers.length;j++)
			realStateMarkers[j].gmaps.setMap(null);			
}
function removeRealState(data){
	var newRealEstatesArray = new Array();
	
	for(var i=0;i<realStateMarkers.length;i++){
		var found=false;
		for(var j=0;j<data.length;j++){
			if(realStateMarkers[i].data.idrealestates == data[j].idrealestates)
			{
				newRealEstatesArray.push(realStateMarkers[i]);
				found=true; break;
			}
		}
		if(found==false){
			removeMarker(realStateMarkers[i].gmaps);		
			$("#"+realStateMarkers[i].data.idrealestates).remove();
		}
	}
	realStateMarkers= newRealEstatesArray;
}

function normalizePrice(data){
	var max = 0;
	for (i=0;i< data.length; i++)
		if ((parseInt(data[i].price) > max)) max = parseInt(data[i].price);
	
	for(i=0;i<data.length;i++){
		data[i].price_avaliation = 100-(parseInt(data[i].price)/max)*100;
	}
}
function addRealEstate(origin,radius){
	$.post(base_url + "realestates/get",
		{
			'lat' :origin.lat(),
			'lng' : origin.lng(),
			'radius' :  radius/(1000*1.609344),
			'type' : filter.type
		},
		function(data){
			data=$.parseJSON(data);
			removeRealState(data);
			normalizePrice(data);
			updateQtyRealEstates(data.length);
			
			for(i=0;i<data.length;i++){
				var found=false;
				for(var j=0;j<realStateMarkers.length;j++){
					if(data[i].idrealestates==realStateMarkers[j].data.idrealestates)
					{
						found=true;break;
					}
				}
				if(!found)
					realStateMarkers.push({gmaps: createRealEstateMarker(data[i]), data:data[i]});
			}	
				
			orderRealEstate();
			
			if(!showedRealEstate && idRealEstate){
				$("#"+idRealEstate).trigger("mouseover");
				showedRealEstate=true;
			}
		}
	);
}
function orderRealEstate(){
	$('.real_state_detail').sortElements(function(a, b){
		return $(a).find(".index").text() < $(b).find(".index").text() ? 1 : -1;
	});
}
function recalculateRealEstate(){
	for(i=0;i<realStateMarkers.length;i++){
		var index = calculateIndex(realStateMarkers[i].data);
		realStateMarkers[i].gmaps.setIcon(indexIcon(index));
		$("#"+realStateMarkers[i].data.idrealestates).find(".index").text(index);
		$("#"+realStateMarkers[i].data.idrealestates).find(".progress").css("width",index+"%");
	}
	orderRealEstate();
}
function updateMarkers(index){
	if(index>=markers.length) return;
	var marker=markers[index];
	reverseGeocode(marker.getPosition(),function(address){
		updateDistrict(address);
		address = address.formatted_address;
		marker.item.find(".name").html(address);
		setVisibleSubmarkers(marker,false);
		marker.submarkers=new Array();
		searchPlaces(marker,marker.getPosition(),marker.radius);
		updateMarkers(++index);
	});
}

var Latinise={};Latinise.latin_map={"Á":"A","Ă":"A","Ắ":"A","Ặ":"A","Ằ":"A","Ẳ":"A","Ẵ":"A","Ǎ":"A","Â":"A","Ấ":"A","Ậ":"A","Ầ":"A","Ẩ":"A","Ẫ":"A","Ä":"A","Ǟ":"A","Ȧ":"A","Ǡ":"A","Ạ":"A","Ȁ":"A","À":"A","Ả":"A","Ȃ":"A","Ā":"A","Ą":"A","Å":"A","Ǻ":"A","Ḁ":"A","Ⱥ":"A","Ã":"A","Ꜳ":"AA","Æ":"AE","Ǽ":"AE","Ǣ":"AE","Ꜵ":"AO","Ꜷ":"AU","Ꜹ":"AV","Ꜻ":"AV","Ꜽ":"AY","Ḃ":"B","Ḅ":"B","Ɓ":"B","Ḇ":"B","Ƀ":"B","Ƃ":"B","Ć":"C","Č":"C","Ç":"C","Ḉ":"C","Ĉ":"C","Ċ":"C","Ƈ":"C","Ȼ":"C","Ď":"D","Ḑ":"D","Ḓ":"D","Ḋ":"D","Ḍ":"D","Ɗ":"D","Ḏ":"D","ǲ":"D","ǅ":"D","Đ":"D","Ƌ":"D","Ǳ":"DZ","Ǆ":"DZ","É":"E","Ĕ":"E","Ě":"E","Ȩ":"E","Ḝ":"E","Ê":"E","Ế":"E","Ệ":"E","Ề":"E","Ể":"E","Ễ":"E","Ḙ":"E","Ë":"E","Ė":"E","Ẹ":"E","Ȅ":"E","È":"E","Ẻ":"E","Ȇ":"E","Ē":"E","Ḗ":"E","Ḕ":"E","Ę":"E","Ɇ":"E","Ẽ":"E","Ḛ":"E","Ꝫ":"ET","Ḟ":"F","Ƒ":"F","Ǵ":"G","Ğ":"G","Ǧ":"G","Ģ":"G","Ĝ":"G","Ġ":"G","Ɠ":"G","Ḡ":"G","Ǥ":"G","Ḫ":"H","Ȟ":"H","Ḩ":"H","Ĥ":"H","Ⱨ":"H","Ḧ":"H","Ḣ":"H","Ḥ":"H","Ħ":"H","Í":"I","Ĭ":"I","Ǐ":"I","Î":"I","Ï":"I","Ḯ":"I","İ":"I","Ị":"I","Ȉ":"I","Ì":"I","Ỉ":"I","Ȋ":"I","Ī":"I","Į":"I","Ɨ":"I","Ĩ":"I","Ḭ":"I","Ꝺ":"D","Ꝼ":"F","Ᵹ":"G","Ꞃ":"R","Ꞅ":"S","Ꞇ":"T","Ꝭ":"IS","Ĵ":"J","Ɉ":"J","Ḱ":"K","Ǩ":"K","Ķ":"K","Ⱪ":"K","Ꝃ":"K","Ḳ":"K","Ƙ":"K","Ḵ":"K","Ꝁ":"K","Ꝅ":"K","Ĺ":"L","Ƚ":"L","Ľ":"L","Ļ":"L","Ḽ":"L","Ḷ":"L","Ḹ":"L","Ⱡ":"L","Ꝉ":"L","Ḻ":"L","Ŀ":"L","Ɫ":"L","ǈ":"L","Ł":"L","Ǉ":"LJ","Ḿ":"M","Ṁ":"M","Ṃ":"M","Ɱ":"M","Ń":"N","Ň":"N","Ņ":"N","Ṋ":"N","Ṅ":"N","Ṇ":"N","Ǹ":"N","Ɲ":"N","Ṉ":"N","Ƞ":"N","ǋ":"N","Ñ":"N","Ǌ":"NJ","Ó":"O","Ŏ":"O","Ǒ":"O","Ô":"O","Ố":"O","Ộ":"O","Ồ":"O","Ổ":"O","Ỗ":"O","Ö":"O","Ȫ":"O","Ȯ":"O","Ȱ":"O","Ọ":"O","Ő":"O","Ȍ":"O","Ò":"O","Ỏ":"O","Ơ":"O","Ớ":"O","Ợ":"O","Ờ":"O","Ở":"O","Ỡ":"O","Ȏ":"O","Ꝋ":"O","Ꝍ":"O","Ō":"O","Ṓ":"O","Ṑ":"O","Ɵ":"O","Ǫ":"O","Ǭ":"O","Ø":"O","Ǿ":"O","Õ":"O","Ṍ":"O","Ṏ":"O","Ȭ":"O","Ƣ":"OI","Ꝏ":"OO","Ɛ":"E","Ɔ":"O","Ȣ":"OU","Ṕ":"P","Ṗ":"P","Ꝓ":"P","Ƥ":"P","Ꝕ":"P","Ᵽ":"P","Ꝑ":"P","Ꝙ":"Q","Ꝗ":"Q","Ŕ":"R","Ř":"R","Ŗ":"R","Ṙ":"R","Ṛ":"R","Ṝ":"R","Ȑ":"R","Ȓ":"R","Ṟ":"R","Ɍ":"R","Ɽ":"R","Ꜿ":"C","Ǝ":"E","Ś":"S","Ṥ":"S","Š":"S","Ṧ":"S","Ş":"S","Ŝ":"S","Ș":"S","Ṡ":"S","Ṣ":"S","Ṩ":"S","Ť":"T","Ţ":"T","Ṱ":"T","Ț":"T","Ⱦ":"T","Ṫ":"T","Ṭ":"T","Ƭ":"T","Ṯ":"T","Ʈ":"T","Ŧ":"T","Ɐ":"A","Ꞁ":"L","Ɯ":"M","Ʌ":"V","Ꜩ":"TZ","Ú":"U","Ŭ":"U","Ǔ":"U","Û":"U","Ṷ":"U","Ü":"U","Ǘ":"U","Ǚ":"U","Ǜ":"U","Ǖ":"U","Ṳ":"U","Ụ":"U","Ű":"U","Ȕ":"U","Ù":"U","Ủ":"U","Ư":"U","Ứ":"U","Ự":"U","Ừ":"U","Ử":"U","Ữ":"U","Ȗ":"U","Ū":"U","Ṻ":"U","Ų":"U","Ů":"U","Ũ":"U","Ṹ":"U","Ṵ":"U","Ꝟ":"V","Ṿ":"V","Ʋ":"V","Ṽ":"V","Ꝡ":"VY","Ẃ":"W","Ŵ":"W","Ẅ":"W","Ẇ":"W","Ẉ":"W","Ẁ":"W","Ⱳ":"W","Ẍ":"X","Ẋ":"X","Ý":"Y","Ŷ":"Y","Ÿ":"Y","Ẏ":"Y","Ỵ":"Y","Ỳ":"Y","Ƴ":"Y","Ỷ":"Y","Ỿ":"Y","Ȳ":"Y","Ɏ":"Y","Ỹ":"Y","Ź":"Z","Ž":"Z","Ẑ":"Z","Ⱬ":"Z","Ż":"Z","Ẓ":"Z","Ȥ":"Z","Ẕ":"Z","Ƶ":"Z","Ĳ":"IJ","Œ":"OE","ᴀ":"A","ᴁ":"AE","ʙ":"B","ᴃ":"B","ᴄ":"C","ᴅ":"D","ᴇ":"E","ꜰ":"F","ɢ":"G","ʛ":"G","ʜ":"H","ɪ":"I","ʁ":"R","ᴊ":"J","ᴋ":"K","ʟ":"L","ᴌ":"L","ᴍ":"M","ɴ":"N","ᴏ":"O","ɶ":"OE","ᴐ":"O","ᴕ":"OU","ᴘ":"P","ʀ":"R","ᴎ":"N","ᴙ":"R","ꜱ":"S","ᴛ":"T","ⱻ":"E","ᴚ":"R","ᴜ":"U","ᴠ":"V","ᴡ":"W","ʏ":"Y","ᴢ":"Z","á":"a","ă":"a","ắ":"a","ặ":"a","ằ":"a","ẳ":"a","ẵ":"a","ǎ":"a","â":"a","ấ":"a","ậ":"a","ầ":"a","ẩ":"a","ẫ":"a","ä":"a","ǟ":"a","ȧ":"a","ǡ":"a","ạ":"a","ȁ":"a","à":"a","ả":"a","ȃ":"a","ā":"a","ą":"a","ᶏ":"a","ẚ":"a","å":"a","ǻ":"a","ḁ":"a","ⱥ":"a","ã":"a","ꜳ":"aa","æ":"ae","ǽ":"ae","ǣ":"ae","ꜵ":"ao","ꜷ":"au","ꜹ":"av","ꜻ":"av","ꜽ":"ay","ḃ":"b","ḅ":"b","ɓ":"b","ḇ":"b","ᵬ":"b","ᶀ":"b","ƀ":"b","ƃ":"b","ɵ":"o","ć":"c","č":"c","ç":"c","ḉ":"c","ĉ":"c","ɕ":"c","ċ":"c","ƈ":"c","ȼ":"c","ď":"d","ḑ":"d","ḓ":"d","ȡ":"d","ḋ":"d","ḍ":"d","ɗ":"d","ᶑ":"d","ḏ":"d","ᵭ":"d","ᶁ":"d","đ":"d","ɖ":"d","ƌ":"d","ı":"i","ȷ":"j","ɟ":"j","ʄ":"j","ǳ":"dz","ǆ":"dz","é":"e","ĕ":"e","ě":"e","ȩ":"e","ḝ":"e","ê":"e","ế":"e","ệ":"e","ề":"e","ể":"e","ễ":"e","ḙ":"e","ë":"e","ė":"e","ẹ":"e","ȅ":"e","è":"e","ẻ":"e","ȇ":"e","ē":"e","ḗ":"e","ḕ":"e","ⱸ":"e","ę":"e","ᶒ":"e","ɇ":"e","ẽ":"e","ḛ":"e","ꝫ":"et","ḟ":"f","ƒ":"f","ᵮ":"f","ᶂ":"f","ǵ":"g","ğ":"g","ǧ":"g","ģ":"g","ĝ":"g","ġ":"g","ɠ":"g","ḡ":"g","ᶃ":"g","ǥ":"g","ḫ":"h","ȟ":"h","ḩ":"h","ĥ":"h","ⱨ":"h","ḧ":"h","ḣ":"h","ḥ":"h","ɦ":"h","ẖ":"h","ħ":"h","ƕ":"hv","í":"i","ĭ":"i","ǐ":"i","î":"i","ï":"i","ḯ":"i","ị":"i","ȉ":"i","ì":"i","ỉ":"i","ȋ":"i","ī":"i","į":"i","ᶖ":"i","ɨ":"i","ĩ":"i","ḭ":"i","ꝺ":"d","ꝼ":"f","ᵹ":"g","ꞃ":"r","ꞅ":"s","ꞇ":"t","ꝭ":"is","ǰ":"j","ĵ":"j","ʝ":"j","ɉ":"j","ḱ":"k","ǩ":"k","ķ":"k","ⱪ":"k","ꝃ":"k","ḳ":"k","ƙ":"k","ḵ":"k","ᶄ":"k","ꝁ":"k","ꝅ":"k","ĺ":"l","ƚ":"l","ɬ":"l","ľ":"l","ļ":"l","ḽ":"l","ȴ":"l","ḷ":"l","ḹ":"l","ⱡ":"l","ꝉ":"l","ḻ":"l","ŀ":"l","ɫ":"l","ᶅ":"l","ɭ":"l","ł":"l","ǉ":"lj","ſ":"s","ẜ":"s","ẛ":"s","ẝ":"s","ḿ":"m","ṁ":"m","ṃ":"m","ɱ":"m","ᵯ":"m","ᶆ":"m","ń":"n","ň":"n","ņ":"n","ṋ":"n","ȵ":"n","ṅ":"n","ṇ":"n","ǹ":"n","ɲ":"n","ṉ":"n","ƞ":"n","ᵰ":"n","ᶇ":"n","ɳ":"n","ñ":"n","ǌ":"nj","ó":"o","ŏ":"o","ǒ":"o","ô":"o","ố":"o","ộ":"o","ồ":"o","ổ":"o","ỗ":"o","ö":"o","ȫ":"o","ȯ":"o","ȱ":"o","ọ":"o","ő":"o","ȍ":"o","ò":"o","ỏ":"o","ơ":"o","ớ":"o","ợ":"o","ờ":"o","ở":"o","ỡ":"o","ȏ":"o","ꝋ":"o","ꝍ":"o","ⱺ":"o","ō":"o","ṓ":"o","ṑ":"o","ǫ":"o","ǭ":"o","ø":"o","ǿ":"o","õ":"o","ṍ":"o","ṏ":"o","ȭ":"o","ƣ":"oi","ꝏ":"oo","ɛ":"e","ᶓ":"e","ɔ":"o","ᶗ":"o","ȣ":"ou","ṕ":"p","ṗ":"p","ꝓ":"p","ƥ":"p","ᵱ":"p","ᶈ":"p","ꝕ":"p","ᵽ":"p","ꝑ":"p","ꝙ":"q","ʠ":"q","ɋ":"q","ꝗ":"q","ŕ":"r","ř":"r","ŗ":"r","ṙ":"r","ṛ":"r","ṝ":"r","ȑ":"r","ɾ":"r","ᵳ":"r","ȓ":"r","ṟ":"r","ɼ":"r","ᵲ":"r","ᶉ":"r","ɍ":"r","ɽ":"r","ↄ":"c","ꜿ":"c","ɘ":"e","ɿ":"r","ś":"s","ṥ":"s","š":"s","ṧ":"s","ş":"s","ŝ":"s","ș":"s","ṡ":"s","ṣ":"s","ṩ":"s","ʂ":"s","ᵴ":"s","ᶊ":"s","ȿ":"s","ɡ":"g","ᴑ":"o","ᴓ":"o","ᴝ":"u","ť":"t","ţ":"t","ṱ":"t","ț":"t","ȶ":"t","ẗ":"t","ⱦ":"t","ṫ":"t","ṭ":"t","ƭ":"t","ṯ":"t","ᵵ":"t","ƫ":"t","ʈ":"t","ŧ":"t","ᵺ":"th","ɐ":"a","ᴂ":"ae","ǝ":"e","ᵷ":"g","ɥ":"h","ʮ":"h","ʯ":"h","ᴉ":"i","ʞ":"k","ꞁ":"l","ɯ":"m","ɰ":"m","ᴔ":"oe","ɹ":"r","ɻ":"r","ɺ":"r","ⱹ":"r","ʇ":"t","ʌ":"v","ʍ":"w","ʎ":"y","ꜩ":"tz","ú":"u","ŭ":"u","ǔ":"u","û":"u","ṷ":"u","ü":"u","ǘ":"u","ǚ":"u","ǜ":"u","ǖ":"u","ṳ":"u","ụ":"u","ű":"u","ȕ":"u","ù":"u","ủ":"u","ư":"u","ứ":"u","ự":"u","ừ":"u","ử":"u","ữ":"u","ȗ":"u","ū":"u","ṻ":"u","ų":"u","ᶙ":"u","ů":"u","ũ":"u","ṹ":"u","ṵ":"u","ᵫ":"ue","ꝸ":"um","ⱴ":"v","ꝟ":"v","ṿ":"v","ʋ":"v","ᶌ":"v","ⱱ":"v","ṽ":"v","ꝡ":"vy","ẃ":"w","ŵ":"w","ẅ":"w","ẇ":"w","ẉ":"w","ẁ":"w","ⱳ":"w","ẘ":"w","ẍ":"x","ẋ":"x","ᶍ":"x","ý":"y","ŷ":"y","ÿ":"y","ẏ":"y","ỵ":"y","ỳ":"y","ƴ":"y","ỷ":"y","ỿ":"y","ȳ":"y","ẙ":"y","ɏ":"y","ỹ":"y","ź":"z","ž":"z","ẑ":"z","ʑ":"z","ⱬ":"z","ż":"z","ẓ":"z","ȥ":"z","ẕ":"z","ᵶ":"z","ᶎ":"z","ʐ":"z","ƶ":"z","ɀ":"z","ﬀ":"ff","ﬃ":"ffi","ﬄ":"ffl","ﬁ":"fi","ﬂ":"fl","ĳ":"ij","œ":"oe","ﬆ":"st","ₐ":"a","ₑ":"e","ᵢ":"i","ⱼ":"j","ₒ":"o","ᵣ":"r","ᵤ":"u","ᵥ":"v","ₓ":"x"};
String.prototype.latinise=function(){return this.replace(/[^A-Za-z0-9\[\] ]/g,function(a){return Latinise.latin_map[a]||a})};
String.prototype.latinize=String.prototype.latinise;
String.prototype.isLatin=function(){return this==this.latinise()};