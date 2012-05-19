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
 