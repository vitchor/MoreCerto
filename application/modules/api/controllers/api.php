<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller{

	public function __construct(){
		  parent::__construct();
	}

	public function index(){
	}	
	public function fast(){
		$this->load->view("fast");
	}
	
	public function status(){		
		$all = $this->db->get("realestates")->num_rows();		
		$active = $this->db->get_where("realestates",array("active"=>true))->num_rows();
		
		$this->db->where("lat IS NOT NULL",NULL);
		$geocode = $this->db->get("realestates")->num_rows();
		
		$this->db->where("review",true);
		$review = $this->db->get("realestates")->num_rows();
		echo "Cadastrados: ".$all;
		echo "<br>";
		echo "Ativos: ".$active;
		echo "<br>";
		echo "Geocode: ".$geocode;
		echo "<br>";
		echo "Precisam ser verificados os endereços: ".$review;
	}
	public function cacheimages(){
		ini_set("allow_url_fopen", 1); 
		ini_set("allow_url_include", 1); 
		$this->load->helper("file_upload");
		$path = "./uploads/cache/";		
		
		$this->db->limit("5");
		$results = $this->db->get_where("realestates",array("cached_image"=>false));
		foreach ($results->result() as $r){
			$file = $path.$r->idrealestates.".jpg";
			file_put_contents($file, file_get_contents($r->thumb));
			smart_resize_image($file,180,150,true,$file,false,false);
			$this->db->where("idrealestates",$r->idrealestates);
			$this->db->update("realestates",array("cached_image"=>true));	
		}
	}
	public function get_places(){
		$this->load->helper("api");
		
		$this->db->limit(20);		
		$this->db->where("lat IS NOT NULL",NULL);
		$this->db->where("lng IS NOT NULL",NULL);
		$this->db->where("current_radius <",MAX_RADIUS);
		$this->db->order_by("current_radius","desc");
		$results = $this->db->get("realestates");
		$counter = 0;
		foreach ($results->result() as $r){
			$latitude = $r->lat;
			$longitude = $r->lng;

			if($r->current_radius==0) getPlaces($latitude,$longitude,array(START_RADIUS));
			else getPlaces($latitude,$longitude,array($r->current_radius+RADIUS_STEP));
			
			if($r->current_radius==0){
					$this->db->where("idrealestates",$r->idrealestates);
					$this->db->update("realestates",array("current_radius"=>START_RADIUS));
				}
				else {
					$this->db->where("idrealestates",$r->idrealestates);
					if(($r->current_radius+RADIUS_STEP)>MAX_RADIUS) 
						$this->db->update("realestates",array("current_radius"=>MAX_RADIUS));
					else $this->db->update("realestates",array("current_radius"=>($r->current_radius+RADIUS_STEP)));
			}		
			usleep(50000);
		} 		
		echo json_encode(array("novos"=>$counter)); 
	}
	public function add_avaliations(){
		$this->load->helper("api");
		
		$this->db->limit(10);
		$this->db->where("active",false);
		$this->db->where("current_radius",MAX_RADIUS);
		$real_estates=$this->db->get("realestates");
		
		if($real_estates->num_rows==0) return;
		
		foreach($real_estates->result() as $real_estate){
			$result = avaliateArea($real_estate->lat, $real_estate->lng);
			
			$this->db->where("idavaliation",$real_estate->fidavaliation);
			$this->db->update('avaliations', $result);

			$this->db->where("idrealestates",$real_estate->idrealestates);
			$this->db->update('realestates',array("active"=>true));		
		}		
	}	
	
	/*Clients*/	
	public function brognoli_get($start=0,$tipo='aluguel'){	
		ini_set("allow_url_fopen", 1); 
		ini_set("allow_url_include", 1);
		 
		$endCompletoPatt = "/t.enderecoCompleto = (.*);/";
		$thumbPatt = "/t.thumb = '(.*)'/";
		$codPatt = "/t.codImovel = '(.*)'/";
		$valorPatt = "/t.valor = '(.*)'/";

		for($page=$start;$page<$start+5;$page++){
			$url = "http://www.brognoli.com.br/busca.php?order=codigo&busca=avancada&imovel_classificacao=&cod_imovel=&opcao=".$tipo."&finalidade=residencial&foto=on&cidades=&regiao=&valor=&mobilia=0&andar=&buscar.x=62&buscar.y=18&num=".$page;
				
			$website = file_get_contents($url);
			$website = mb_convert_encoding($website, "UTF-8", "Windows-1252");
		
			preg_match_all($endCompletoPatt, $website, $endCompletos);
			preg_match_all($thumbPatt, $website, $thumbs);
			preg_match_all($codPatt, $website, $cods);
			preg_match_all($valorPatt, $website, $prices);

			
			for($i=0;$i< count($endCompletos[1]) ;$i++){
				$code = $cods[1][$i];
				$thumb = $thumbs[1][$i];
				$fulladdress = str_replace("'", "",$endCompletos[1][$i]);
				
				$itens = explode(",", $fulladdress);
			
				$street = $itens[0];
				$number=$itens[1];
				
				$itens = explode(" - ",$itens[2]);
				
				$district=$itens[0];
				$city=$itens[1];
				$state = $itens[2];
				
				$price = str_replace(",",".",str_replace(".","",str_replace("R$","",$prices[1][$i])));
				
				if(!$city || !$district || !$state) continue;
				
				$results = $this->db->get_where("realestates",array("code"=>$cods[1][$i]));
				if($results->num_rows()==0){
					$this->db->insert("avaliations",array("bank"=>0,"store"=>0,"market"=>0,"gas_station"=>0,"health"=>0,"restaurant"=>0,"bar"=>0));
					$fid_avaliation =$this->db->insert_id(); 
					$real_estate = array(
						"address" =>$fulladdress,
						"thumb" =>$thumb,
						"code" => $code,
						"type"=>'rent',
						"city"=>$city,
						"state"=>$state,
						"street"=>$street,				
						"number"=>$number,
						"district"=>$district,
						"price"=>$price,
						"fidavaliation" =>$fid_avaliation,
						"url"=> ("http://www.brognoli.com.br/imovel/" . $code),
						"created"=>date('Y-m-d H:i:s'),
						"agency"=>"Brognoli",
						"kind"=>"apt"
						);				
					$this->db->insert("realestates",$real_estate);
				}					
			}
			usleep(100000);
		}
	}	
public function ufsc_iframe(){
		$this->load->view("ufsc_iframe");
	}
	public function get_ufsc(){
		ini_set("allow_url_fopen", 1); 
		ini_set("allow_url_include", 1);

		$json =json_decode(file_get_contents("http://classificados.inf.ufsc.br/morecerto.php?key=d353c3ae22a25401d257643836d"));
		$items = array();
		foreach($json as $r ){
			$db =array();
			if($r->imagem=="" || $r->imagem== NULL)
				$db["thumb"]="";
			else $db["thumb"]="http://classificados.inf.ufsc.br/images/" . $r->imagem;
			
			$db["address"] = $r->LA_LOGRADOURO." " . $r->LA_BAIRRO . " ".$r->LA_CIDADE;
			$db["url"] = "http://classificados.inf.ufsc.br/detail.php?id=".$r->id;
		
			$type="rent";
			$kind="apt";
			switch($r->categoria){
				case "86":
				case "87":
				case "88":
				case "89":
				case "90":
				case "91":				
				case "94":				
				case "96":
				case "197":
					$kind = "room";
					$type="rent";
					break;
				case "72":
				case "74":
					$kind= "apt";
					$type="rent";
					break;
				case "73":
					$kind = "house";
					$type = "rent";
					break;
				case "74":
					$kind = "kit";
					$type="rent";
					break;
				case "76":
				case "141":
					$kind = "apt";
					$type="buy";
					break;
			}
			
			$multi_thousand = strpos($r->LA_PRECO,"mil") != 0;
			$price = preg_replace("/[^0-9,.]/", "", $r->LA_PRECO);
			if($type=="buy"){
				$price = str_replace(".", "", $price);
				$price = str_replace(",", ".", $price);
			}
			else {
				if(strpos($price,",")>0 && strpos($price,".")){
					$price = str_replace(".", "", $price);
					$price = str_replace(",", ".", $price);
				}
				else{
					$price = str_replace(",", ".", $price);	
				}				
			}
			$price = intval($price);
			if($multi_thousand) $price *= 1000;
			$db["price"] = $price;
			$db["type"] = $type;
			$db["kind"] = $kind;
			$db["agency"] =""; 
			$db["id"] =$r->id;
			array_push($items, $db);
		}
		$data = array("data"=>$items,"json"=>json_encode($items));
	  	$this->load->vars($data);
	  	view('api/upload');
	}
}

?>