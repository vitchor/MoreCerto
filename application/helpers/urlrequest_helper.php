<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('url'))
{
	function url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url($uri);
	}
}
if ( ! function_exists('getResponseArray')){
	function getResponseArray($error,$msg,$param){
		return (array('error' => $error , 'msg' => $msg,'param'=>$param));
	}
}
if ( ! function_exists('getResponseArrayJson')){
	function getResponseArrayJson($error,$msg,$param=NULL){
		print json_encode(getResponseArray($error,$msg,$param));
	}
}
if ( ! function_exists('getNotLoggedJson')){
	function getNotLoggedJson(){
		print json_encode(array('notlogged'=>true));
	}
}
if ( ! function_exists('getResponseJson')){
	function getResponseJson($array){
		print json_encode($array);
	}
}

if ( ! function_exists('isPostRequest')){
	function isPostRequest(){
		return $_SERVER['REQUEST_METHOD'] == "POST";		
	}
}

if ( ! function_exists('isGetRequest')){
	function isGetRequest(){
		return $_SERVER['REQUEST_METHOD'] == "GET";	
	}
}
