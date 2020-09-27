<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cms_event
{
	public $CI;
	public function __construct()
	{
		$this->CI 			= &get_instance();
	}
	
	function call_api($xml_post_string = '')
	{
		
	}
	
}
?>