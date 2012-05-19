<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller{

	public function __construct(){
		  parent::__construct();
	}

	public function index(){
		if($this->input->get("_escaped_fragment_")){
			
			$param = urldecode($this->input->get("_escaped_fragment_")); 
			$state= substr($param,1,2);
			$city = substr($param,4);
			
			$result = $this->db->get_where("cities",array("name"=>$city,"state"=>$state));
			
			if($result->num_rows()>0){
				$result = $result->row();
				
				$lat = $result->lat;
				$lng = $result->lng;
				$radius = 3;
				
				$query = "SELECT realestates.*, avaliations.*, " .
				"3956 * 2 * ASIN(SQRT( POWER(SIN((".$lat." - realestates.lat) * pi()/180 / 2), 2) +COS(". $lat . " * pi()/180) * COS(realestates.lat * pi()/180) *  ".
				"POWER(SIN((".$lng. " -realestates.lng) * pi()/180 / 2), 2) )) as distance FROM realestates ".
				" inner join avaliations on avaliations.idavaliation = realestates.fidavaliation ".
				" where active = true ".
				"having distance < " .$radius . " order by distance limit 100";		
		
				$realestates = $this->db->query($query);
				$this->load->vars("realestates",$realestates);
				$this->load->vars("city",$city);
				$this->load->vars("state",$state);
				$this->load->vars("city_name",$result->complete_name);
				view("index/index_crawler");
			}
		}
		else view("index/index");
	}			
	public function comofunciona(){
		$this->load->vars("howitworks",true);
		view("index/index");
	}	
	public function anuncie(){
		view("index/ads");
	}
}

?>