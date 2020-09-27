<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_model extends CI_Model {

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
		}
		
		public function get_products()
		{
			$this->db->select('pm.*,ct.*');
			$this->db->from('products as pm');
			$this->db->join('categories as ct','pm.product_cat = ct.cat_id','LEFT');
			$this->db->order_by('RAND()');
			$this->db->limit(10);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->result_array();
				return $resultData;
			}
			return false;
		}
		
		public function get_product_by_title($title)
		{
			$this->db->select('*');
			$this->db->from('products');
			$this->db->where('product_title',$title);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->row_array();
				return $resultData;
			}
			return false;
		}
		
		public function get_cart_by_id($data = array())
		{
			$this->db->select('*');
			$this->db->from('cart');
			if(isset($data['p_id']) && $data['p_id']!="")
			{
				$this->db->where('p_id',(int)$data['p_id']);
			}
			if(isset($data['user_id']) && $data['user_id']!="")
			{
				$this->db->where('user_id',(int)$data['user_id']);
			}
			if(isset($data['ip_add']) && $data['ip_add']!="")
			{
				$this->db->where('ip_add',$data['ip_add']);
			}
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->row_array();
				return $resultData;
			}
			return false;
		}
		
		public function get_cart_count($data = array())
		{
			$this->db->select('id');
			$this->db->from('cart');
			if(isset($data['p_id']) && $data['p_id']!="")
			{
				$this->db->where('p_id',(int)$data['p_id']);
			}
			if(isset($data['user_id']) && $data['user_id']!="")
			{
				$this->db->where('user_id',(int)$data['user_id']);
			}
			if(isset($data['ip_add']) && $data['ip_add']!="")
			{
				$this->db->where('ip_add',$data['ip_add']);
			}
			$query = $this->db->get();
			$total_records =   $query->num_rows();
			return  $total_records;
		}
		
		public function insert_cart($arrData = array())
		{
			$this->db->insert('cart',$arrData);
			$fldi_id = $this->db->insert_id();
			return $fldi_id;
		}
		public function update_cart_by_id($arrData = array(),$user_id = 0,$p_id = 0)
		{
			$this->db->where('user_id',$user_id);
			$this->db->where('p_id',$p_id);
			$this->db->update('cart',$arrData);
			return true;
		}
		public function add_order($arrData = array())
		{
			$this->db->insert('orders_info',$arrData);
			$fldi_id = $this->db->insert_id();
			return $fldi_id;
		}
		public function add_order_products($arrData = array())
		{
			$this->db->insert('order_products',$arrData);
			$fldi_id = $this->db->insert_id();
			return $fldi_id;
		}
		public function update_cart_by_ip($arrData = array(),$ip_add = 0,$p_id = 0)
		{
			$this->db->where('ip_add',$ip_add);
			$this->db->where('p_id',$p_id);
			$this->db->update('cart',$arrData);
			return true;
		}
		
		public function update_cart_by_register($arrData = array(),$ip_add = 0)
		{
			$this->db->where('ip_add',$ip_add);
			$this->db->update('cart',$arrData);
			return true;
		}
		
		public function delete_cart($data = array())
		{
			if(isset($data['p_id']) && $data['p_id']!="")
			{
				$this->db->where('p_id',(int)$data['p_id']);
			}
			if(isset($data['user_id']) && $data['user_id']!="")
			{
				$this->db->where('user_id',(int)$data['user_id']);
			}
			if(isset($data['ip_add']) && $data['ip_add']!="")
			{
				$this->db->where('ip_add',$data['ip_add']);
			}
			$this->db->delete('cart');
		}
		public function delete_order_products($order_id)
		{
			$this->db->where('order_id ',$order_id);
			$this->db->delete('order_products');
		}
		
		public function get_cart_product_details()
		{
			$this->db->select('pm.*,ct.*');
			$this->db->from('products as pm');
			$this->db->join('cart as ct','pm.product_id = ct.p_id');
			if(isset($data['user_id']) && $data['user_id']!="")
			{
				$this->db->where('ct.user_id',(int)$data['user_id']);
			}
			if(isset($data['ip_add']) && $data['ip_add']!="")
			{
				$this->db->where('ct.ip_add',$data['ip_add']);
			}
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->result_array();
				return $resultData;
			}
			return false;
		}
		
		public function get_user_details($data = array())
		{
			$this->db->select('*');
			$this->db->from('user_info');
			if(isset($data['email']) && $data['email']!="")
			{
				$this->db->where('email',$data['email']);
			}
			if(isset($data['password']) && $data['password']!="")
			{
				$this->db->where('password',$data['password']);
			}
			if(isset($data['user_id']) && $data['user_id']!="")
			{
				$this->db->where('user_id',(int)$data['user_id']);
			}
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->row_array();
				return $resultData;
			}
			return false;
		}
		public function add_user($arrData = array())
		{
			$this->db->insert('user_info',$arrData);
			$fldi_id = $this->db->insert_id();
			return $fldi_id;
		}
		
		public function get_order_product_details($order_id)
		{
			$this->db->select('op.*,pm.*');
			$this->db->from('order_products as op');
			$this->db->join('products as pm','op.product_id  = pm.product_id');
			$this->db->where('order_id ',(int)$order_id);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$resultData =  $query->result_array();
				return $resultData;
			}
			return false;
		}
}