<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('updateType')){
	function updateType(&$avaliations,$realestate_types,$value){
			foreach($avaliations as &$avaliation){
				for($i=0; $i< count($avaliation["types"]);$i++){
					$type=$avaliation["types"][$i];
					foreach($realestate_types as $realestate_type){
						if(strcmp($realestate_type, $type)==0){
							$avaliation["qty"]++;
							$avaliation["sum"]+=$value;
						}
					}
				}				 
			}		
	}
}

if ( ! function_exists('calculateType')){
		function calculateType($distance,$radius){
			$rate = 0.3;
			$finalValue = 0.5;		
			if($distance <= $rate*$radius)
				return 100;
			else return (1 - $finalValue*($distance - $rate*$radius)/((1-$rate)*$radius))*100;
		}
}

if ( ! function_exists('geocodeAddress')){
		function geocodeAddress($address){
			ini_set("allow_url_fopen", 1); 
			ini_set("allow_url_include", 1); 
			
			include_once("GoogleMap.php");
			include_once("JSMin.php");
			
			$MAP_OBJECT = new GoogleMapAPI();
			$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
			
			$geocodes = $MAP_OBJECT->geoGetCoordsFull($address);
		
			if($geocodes->status == "OVER_QUERY_LIMIT"){
				echo "OVER_QUERY_LIMIT";
				return array("status"=>"OVER_QUERY_LIMIT");
			}
			if(count($geocodes->results)==0){
				return array("status"=>"ZERO_RESULTS");
			}
							
			foreach($geocodes->results[0]->address_components as $add)
			{
				switch($add->types[0]){
					case "street_number":
						$realestate["number"]=$add->long_name;
						break;
					case "sublocality":
						$realestate["district"]=$add->long_name;
						break;
					case "route":
						$realestate["street"]=$add->long_name;
						break;
					case "locality":
						$realestate["city"]=$add->long_name;
						break;
					case "administrative_area_level_1":
						$realestate["state"]=$add->short_name;
						break;					
				}
			}
			$location = $geocodes->results[0]->geometry->location;
			$realestate["lat"] =$location->lat;
			$realestate["lng"] =$location->lng;
			if( $geocodes->results[0]->formatted_address!=NULL)
				$realestate["address"] = $geocodes->results[0]->formatted_address;
			return $realestate;
		}
}

if ( ! function_exists('getPlaces')){
		function getPlaces($latitude,$longitude,$radius_array){
			$CI =& get_instance();
			
			ini_set("allow_url_fopen", 1); 
			ini_set("allow_url_include", 1); 
			include_once("GooglePlaces.php");
			$apiKey = 'AIzaSyDT4Sx8a-HSuPKQGjuyXNDa-9Azq_Y8o8M';
			$googlePlaces = new GooglePlaces($apiKey);
			$counter = 0;
			
			foreach($radius_array as $radius){
				$googlePlaces->setLocation($latitude . ',' . $longitude);
				$googlePlaces->setRadius($radius);
				
				$googlePlaces->setTypes("grocery_or_supermarket|food|bakery|gym|hospital|health|dentist|doctor|store|establishment|gas_station|laundry|bank");
				$places = $googlePlaces->Search();
				if($places == NULL)
					continue;
					
				foreach($places->results as $p){
					if($p->geometry->location->lat == NULL || 
						$p->geometry->location->lng == NULL	)
						 return;
						 
					$place_in_db = $CI->db->get_where("places",array("idplaces"=>$p->id));
					if($place_in_db->num_rows()==0){
						if(isset($p->rating)){
							$place = array(
								"lat"=>$p->geometry->location->lat,
								"lng"=>$p->geometry->location->lng,
								"icon"=>$p->icon,
								"name"=>$p->name,
								"reference"=>$p->reference,
								"types"=>json_encode($p->types),
								"address"=>$p->vicinity,
								"idplaces"=>$p->id,		
								"rating"=>$p->rating										
							);	
						}
						else {
							$place = array(
								"lat"=>$p->geometry->location->lat,
								"lng"=>$p->geometry->location->lng,
								"icon"=>$p->icon,
								"name"=>$p->name,
								"reference"=>$p->reference,
								"types"=>json_encode($p->types),
								"address"=>$p->vicinity,
								"idplaces"=>$p->id,											
							);
						}
						
						$CI->db->insert("places",$place);			
					}
				}	
				$counter++;
				if($counter < count($radius_array))
					usleep(20000);	
			}			
	}
}
if ( ! function_exists('avaliateArea')){
		function avaliateArea($lat,$lng){
			$CI =& get_instance();

			$radius = MAX_RADIUS/1000;
			
			$query = "SELECT places.*, " .
			"3956 * 2 * ASIN(SQRT( POWER(SIN((".$lat." - places.lat) * pi()/180 / 2), 2) +COS(". $lat . " * pi()/180) * COS(places.lat * pi()/180) *  ".
			"POWER(SIN((".$lng. " -places.lng) * pi()/180 / 2), 2) )) as distance FROM places having distance < " .$radius . " order by distance";
	
			$places = $CI->db->query($query);
			$result["num_places"]=$places->num_rows();
			
			$types = array(
				array("name"=>"bank","qty" => 0, "sum" => 0, "types"=>array("bank")),
				array("name"=>"store","qty" => 0, "sum" => 0, "types"=> array("store","establishment")),
				array("name"=>"market","qty" => 0, "sum" => 0, "types"=> array("grocery_or_supermarket", "bakery")),
				array("name"=>"gas_station","qty" => 0, "sum" => 0, "types"=>array("gas_station")),
				array("name"=>"restaurant","qty" => 0, "sum" => 0, "types"=>array("food","restaurant")),
				array("name"=>"bar","qty" => 0, "sum" => 0, "types"=>array("bar")),
				array("name"=>"health","qty" => 0, "sum" => 0, "types"=>array("pharmacy","health","hospital")),
			);
			
			foreach($places->result() as $place){
				 updateType($types,json_decode($place->types),calculateType($place->distance,$radius));
			}
			
			$result = array();
			foreach($types as $avaliation){
				if($avaliation["qty"] >0)
					$result[$avaliation["name"]]= ($avaliation["sum"]/$avaliation["qty"]);
				else $result[$avaliation["name"]]= 0.0;
			}		
			
			return $result;				
		}
}