<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crm_auth
{
	private $user_data;
	private $agent_data;
	private $breadcrumb;
	private $user_sub_arr;
	public  $CI;
	public function __construct()
	{
		$this->CI 			= &get_instance();
		$this->breadcrumb 	= array();
		$this->agent_data 	= array();
		
		if($this->is_logged_in())
		{
			$this->user_data 						= $this->getUserDetails($this->get_id());
		}
	}
	
	public function chk_allowed_ip()
	{
		/*$flgReturn 		= false;
		$client_ip		= getClientIP();
		$client_ip_cmp	= substr($client_ip,0,7);
		$this->CI->db->select('fldv_ip');
		$this->CI->db->where('flg_is_deleted',0);
		$this->CI->db->where('fldv_ip',$client_ip);
		$query = $this->CI->db->get('allowed_ipaddress');
		
		if ($query->num_rows() > 0)
		{
			$flgReturn = true;
		}
		
		if($client_ip_cmp == '10.160.')
		{
			$flgReturn = true;
		}*/
		
		$flgReturn = true;
		
		//if no access then redirect to invalid page
		if(!$flgReturn && $_SERVER['REQUEST_URI'] != '/home/invalid')
		{
			redirect(site_url('admin/home/invalid'));
			exit;
		}
	}
	
	public function initialize($admin_id='')
	{
		$this->CI->session->set_userdata('sess_cnxusr_id', (int)$admin_id);
		$this->user_data 		= $this->getUserDetails($admin_id); 
	}
	
	public function getUserDetails($id = '')
	{
		$this->CI->db->where('fldi_admin_id',(int)$id);
		$this->CI->db->where('flg_is_deleted',0);
		$query = $this->CI->db->get('admin_master');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		return false;
	}
	
	public function is_logged_in()
	{
		if($this->CI->session->userdata('sess_cnxusr_id'))
		{
			return true;
		}
		return false;
	}
	
	public function get_id()
	{
		return $this->CI->session->userdata('sess_cnxusr_id');   
	}
	
	public function get_email()
	{
		return $this->user_data['fldv_email'];    
	}
	
	public function get_name()
	{
		return $this->user_data['fldv_name'];     
	}
	
	public function get_mobile()
	{
		return $this->user_data['fldv_mobile'];     
	}
	
	public function get_userpass()
	{
		return $this->user_data['fldv_password'];     
	}
	
	public function get_last_login()
	{
		return date('d M Y H:i',strtotime($this->user_data['flddt_last_login']));     
	}
	
	// (Link will be disabled when it is the last entry, or URL set as '#')
	function push_breadcrumb($name, $url = '#', $append = TRUE)
	{
		$entry = array('name' => $name, 'url' => $url);

		if ($append)
			$this->breadcrumb[] = $entry;
		else
			array_unshift($this->breadcrumb, $entry);
	}
	
	function get_breadcrumb()
	{
		return $this->breadcrumb;   
	}
}
?>