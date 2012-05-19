<?php
if ( ! function_exists('realestateImage')){
	function realestateImage($r){
		if($r->cached_image==true)
			echo base_url()."uploads/cache/".$r->idrealestates.".jpg";
		else echo $r->thumb;
	}	
}
if ( ! function_exists('view')){
	function view($view,$title=''){
		$CI = &get_instance();
		$CI->load->view("header",array("view"=>$view));
		$CI->load->view($view);
		$CI->load->view("footer",array("view"=>$view));
	}	
}
if ( ! function_exists('active')){
	function active($current_view, $views){
		if(is_array($views)){
			foreach($views as $v){
				if($v==$current_view)
					return "active";
			}
			return "";
		}
		elseif($views==$current_view)
			return "active";
		else return "";
	}	
}