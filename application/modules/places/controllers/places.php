<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Places extends CI_Controller{

	public function __construct(){
		  parent::__construct();
	}

	public function index(){

	}	
	public function create(){
		$this->load->view('create');
	}
	
	public function add(){
		$place = array(
			'idplaces' => $this->input->post('id'),
			'lat' => $this->input->post('lat'),
			'lng' => $this->input->post('lng'),
			'name' => $this->input->post('name'),
			'address' => $this->input->post('address'),
			'reference' => $this->input->post('reference'),
			'icon' => $this->input->post('icon'),
			'types' => $this->input->post('types')
			);
		@$this->db->insert('places', $place);
		echo true;
	}	
	
	public function get() {
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$radius = $this->input->post('radius');
		
		$query = "SELECT places.*, " .
		"3956 * 2 * ASIN(SQRT( POWER(SIN((".$lat." - places.lat) * pi()/180 / 2), 2) +COS(". $lat . " * pi()/180) * COS(places.lat * pi()/180) *  ".
		"POWER(SIN((".$lng. " -places.lng) * pi()/180 / 2), 2) )) as distance FROM places ".
		"having distance < " .$radius . " limit 100";		

		$places = $this->db->query($query);
		echo json_encode($places->result());
	}
}

?>