<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class RealEstates extends CI_Controller{

	public function __construct(){
		  parent::__construct();
	}

	public function index(){

	}	
	public function create(){
		$this->load->view('create');
	}
	public function bycity(){
		$this->db->select("city");
		$this->db->select("count(*) as city_count");
		$this->db->group_by("city");
		$this->db->order_by("count(*)","desc");
		$this->db->where("active",true);
		$result = $this->db->get("realestates");
		foreach($result->result() as $city)
			echo $city->city . " " . $city->city_count . "<br>";
			
		echo var_dump($result->result());
	}
	public function ufsc($id){
		$url = "http://classificados.inf.ufsc.br/detail.php?id=".$id;
		$result = $this->db->get_where("realestates",array("url"=>$url,"active"=>true,'review'=>false));
		if($result->num_rows()>0)
		{
			$row= $result->row();
			redirect("realestates/show/".$row->idrealestates);
		}
		else {
			$this->session->set_flashdata("error_realestate",true);
			redirect("index");		
		}
	}
	public function city($state,$city){
		header('Content-type: application/json');
		$data = $this->db->get_where("cities",array("name"=>$city,"state"=>$state));
		if($data->num_rows()>0){
			echo json_encode($data->row());			
		}
		else echo json_encode(NULL);
	}
	public function add_city(){
		header('Content-type: application/json');
		if($this->input->post("short_name")==NULL) die;
		$city =	array(
			"name"=>$this->input->post("short_name"),
			"state"=>$this->input->post("state"),
			"complete_name"=>$this->input->post("long_name"),
			"lat"=>$this->input->post("lat"),
			"lng"=>$this->input->post("lng"),
		);
		$data = $this->db->insert("cities",$city);
		echo true;
	}
	public function upload(){
		require_once('ParseCSV.php');
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';

		$this->load->library('upload', $config);
		
		if(isPostRequest()){
			if ($_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])){
				$csv = new ParseCSV();
				$csv->delimiter=";";
				$csv->parse(file_get_contents($_FILES['userfile']['tmp_name']));
				
	  			$data = array("csv"=>$csv,"json"=>json_encode($csv->data));
	  			$this->load->vars($data);
	  			view('upload_success');
			}	
		}
		else {
			$this->load->vars("error",null);
			view('upload');
		}
	}
	public function edit($id){
		$result = $this->db->get_where("realestates",array("url"=>$this->input->post("url")));
		if($result->num_rows==0){
			$this->db->insert("avaliations",array("bank"=>0,"store"=>0,"market"=>0,"gas_station"=>0,"health"=>0,"restaurant"=>0,"bar"=>0));
			$fid_avaliation =$this->db->insert_id();
			
			$realestate = array(
				"thumb"=>$this->input->post("thumb"),
				"url"=>$this->input->post("url"),
				"type"=>$this->input->post("type"),
				"address"=>$this->input->post("address"),
				"price"=>$this->input->post("price"),
				"created"=>date('Y-m-d H:i:s'),
				"agency"=>$this->input->post("agency"),
				"rooms"=>$this->input->post("rooms"),
				"kind"=>$this->input->post("kind"),
				"fidavaliation"=>$fid_avaliation
			);			
			$this->db->insert("realestates",$realestate);
		}	
		else{
			$realestate=$result->row();
			$this->db->where("idrealestates",$realestate->idrealestates);			
			$this->db->update("realestates",array("price"=>$this->input->post("price")));
		}	
	}
	public function add(){
		header('Content-type: application/json');
		$this->load->helper("api/api");
		
		$result = $this->db->get_where("realestates",array("url"=>$this->input->post("url")));
		if($result->num_rows==0){			
			$geocode = geocodeAddress($this->input->post("address"));
			if(!isset($geocode["lat"]) || !isset($geocode["lng"]) ||
				!isset($geocode["city"]) || !isset($geocode["district"]) ||
				!isset($geocode["state"])){
				echo json_encode(array("added"=>false));
				die();
			}
			getPlaces($geocode["lat"],$geocode["lng"],array("500"));
			$avaliation = avaliateArea($geocode["lat"],$geocode["lng"]);
			
			$this->db->insert("avaliations",$avaliation);
			$fid_avaliation =$this->db->insert_id();
			
			$area=0;
			if($this->input->post("area")) $area=$this->input->post("area");
			
			$realestate = array(
				"thumb"=>$this->input->post("thumb"),
				"url"=>$this->input->post("url"),
				"type"=>$this->input->post("type"),
				"address"=>$this->input->post("address"),
				"price"=>$this->input->post("price"),
				"created"=>date('Y-m-d H:i:s'),
				"agency"=>$this->input->post("agency"),
				"rooms"=>$this->input->post("rooms"),
				"kind"=>$this->input->post("kind"),
				"lat"=>$geocode["lat"],
				"lng"=>$geocode["lng"],
				"city"=>$geocode["city"],
				"state"=>$geocode["state"],
				"district"=>$geocode["district"],
				"street"=>isset($geocode["street"])?$geocode["street"]:"",
				"number"=>isset($geocode["number"])?$geocode["number"]:0,
				"area"=>$area, 
				"fidavaliation"=>$fid_avaliation,
				"current_radius"=>0,
				"active"=>true
			);			
			$this->db->insert("realestates",$realestate);			
			echo json_encode(array("avaliation"=>$avaliation,"id"=>$this->db->insert_id()));
		}	
		else{
			$realestate=$result->row();
			$this->db->where("idrealestates",$realestate->idrealestates);			
			$this->db->update("realestates",array("price"=>$this->input->post("price")));
			echo json_encode(array("added"=>false));
		}	
	}
	public function delete($id){
		if($id!= null){
			$this->db->where("idrealestates",$id);
			$this->db->update("realestates",array("deleted"=>date('Y-m-d H:i:s')));	
		}		
	}
	public function trending(){		
		$this->db->join("realestates","realestates.idrealestates = clicks.fidrealestate");
		$this->db->select_sum("count","clicks");
		$this->db->select("fidrealestate");
		$this->db->select("url");
		$this->db->select("thumb");
		$this->db->where("deleted IS NULL",null,false);
		$this->db->group_by("fidrealestate");
		$this->db->order_by("clicks",'desc');
		$results = $this->db->get("clicks");
 		$this->load->vars("trending",$results->result());
 		$this->load->view("trending"); 		
	}
	public function get() {
		if(isPostRequest()){
			$lat = $this->input->post('lat');
			$lng = $this->input->post('lng');
			$radius = $this->input->post('radius');
			$type= $this->input->post('type');
			
			if($lat==null || $lng==null ||  $radius ==null) return;
			$query = "SELECT realestates.*, avaliations.*, " .
			"3956 * 2 * ASIN(SQRT( POWER(SIN((".$lat." - realestates.lat) * pi()/180 / 2), 2) +COS(". $lat . " * pi()/180) * COS(realestates.lat * pi()/180) *  ".
			"POWER(SIN((".$lng. " -realestates.lng) * pi()/180 / 2), 2) )) as distance FROM realestates ".
			" inner join avaliations on avaliations.idavaliation = realestates.fidavaliation ".
			" where active = true ".
			" and review = false ";
			
			if($type!=null && $type != "")
				$query .= " and type = '".$type."'";
					
			$query .= " and deleted is null ".
			"having distance < " .$radius . " order by distance ";		
	
			$realestates = $this->db->query($query);
			if($realestates->num_rows()>0)
				echo json_encode($realestates->result());
			else{
				echo json_encode(array());
			}
		}		
	}	
	public function click($id){
		if($id != NULL && $id){
			$this->db->where('fidrealestate', $id);
			$this->db->where('date', date('Y-m-d'));
			$this->db->where("ip",$_SERVER['REMOTE_ADDR']);			
			$old = $this->db->get("clicks");
			
			if($old->num_rows()==0){
				$old=$old->row();
				$clicks = array(
					"fidrealestate"=>$id,
					"date"=>date('Y-m-d'),
					"count"=>1,
					"ip"=>$_SERVER['REMOTE_ADDR']	
				);
				$this->db->insert("clicks",$clicks);	
			}
			else {
				$this->db->where('idclick', $old->row()->idclick);
				$this->db->set("count","count+1",FALSE);				
				$this->db->update("clicks");
			}				
		}		
	}
	public function getclicks(){
		$this->load->helper("json");
		header('Content-type: application/json');
		
		$this->db->group_by("year(date), month(date), day(date)");
		$this->db->select_sum("count","clicks");
		$this->db->select("date");  
		$result = $this->db->get("clicks");
		echo json_encode($result->result());
	}
	
	public function userclicks(){
		$this->db->select_sum("count","clicks_sum");
		$result = $this->db->get("clicks");
		echo $result->row()->clicks_sum;
		$this->load->view("clicks");
	}
	
	public function show($id){
		if(isGetRequest()){
			if($id){
				$results= $this->db->get_where("realestates",array("idrealestates"=>$id));
				//$this->session->set_flashdata("realestate",$results->row());
				$this->load->vars("realestate",$results->row());
				view("index/index");
			}	
		}
		if(isPostRequest()){
			header('Content-type: application/json');
			$results= $this->db->get_where("realestates",array("idrealestates"=>$id));
			echo json_encode($results->row());
		}
	}
}

?>