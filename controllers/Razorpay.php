<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @package Razorpay :  CodeIgniter Razorpay Gateway
 *
 * @author TechArise Team
 *
 * @email  info@techarise.com
 *   
 * Description of Razorpay Controller
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Razorpay extends CI_Controller {
    // construct
    public function __construct() {
        parent::__construct();   
		$this->load->library('form_validation');
		$this->load->model('config_model');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
          
    }
    // index page
   /*  public function index() {
        $data['title'] = 'Razorpay | TechArise';  
        $data['productInfo'] = $this->site->getProduct();           
        $this->load->view('razorpay/index', $data);
    }*/
    
    // checkout page
    public function checkout() 
	{
        $data['title'] 	  = 'Kojar Payment | TechKojar';  
        $data['return_url'] = site_url().'razorpay/callback';
        $data['surl'] = site_url().'razorpay/success';
        $data['furl'] = site_url().'razorpay/success';
        $data['currency_code'] = 'INR';
		
		$data['scontent'] = array();
		$data['scontent']['user_id'] = $_SESSION['uid'];
		$row = $this->config_model->get_user_details($data['scontent']);
		
		$i=1;
		$total=0;
		$total_count=$_POST['total_count'];
		while($i<=$total_count){
			$amount_ = $_POST['amount_'.$i];
			$total=$total+$amount_ ;
			$i++;
		}
		$arrData = array();
		$arrData['user_id ']   = $_SESSION['uid'];
		$arrData['total_amt '] = $total;
		$order_id = $this->config_model->add_order($arrData);
		
		$i=1;
		$total=0;
		while($i<=$total_count){
			$item_name_ = $_POST['item_name_'.$i];
			$amount_ 	= $_POST['amount_'.$i];
			$quantity_ 	= $_POST['quantity_'.$i];
			$total=$total+$amount_ ;
			
			$row = $this->config_model->get_product_by_title($item_name_);
			
			$product_id=$row["product_id"];
			$arrData = array();
			$arrData['order_id'] = $order_id;
			$arrData['product_id'] = $product_id;
			$arrData['qty'] = $quantity_;
			$arrData['amt'] = $amount_;
			$id = $this->config_model->add_order_products($arrData);
			
			$i++;
		}
		$_SESSION["order_id"] = $order_id;
		$data['total'] = $total;
		$data['order_id'] = $order_id;
		$data['arrProduct'] = $this->config_model->get_order_product_details($order_id);
        $this->load->view('razorpay/checkout', $data);
    } 

    // initialized cURL Request
    private function get_curl_handle($payment_id, $amount)  {
        $url = 'https://api.razorpay.com/v1/payments/'.$payment_id.'/capture';
        $key_id = 'rzp_test_8zYHHCbyDTgz0y';
        $key_secret = 'GGHx62VQipKBc933C2GDS9B8';
        $fields_string = "amount=$amount";
        //cURL Request
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/ca-bundle.crt');
        return $ch;
    }   
        
    // callback method
    public function callback() {        
        if (!empty($this->input->post('razorpay_payment_id')) && !empty($this->input->post('merchant_order_id'))) {
            $razorpay_payment_id = $this->input->post('razorpay_payment_id');
            $merchant_order_id = $this->input->post('merchant_order_id');
            $currency_code = 'INR';
            $amount = $this->input->post('merchant_total');
            $success = false;
            $error = '';
            try {                
                $ch = $this->get_curl_handle($razorpay_payment_id, $amount);
                //execute post
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($result === false) {
                    $success = false;
                    $error = 'Curl error: '.curl_error($ch);
                } else {
                    $response_array = json_decode($result, true);
                   // echo "<pre>";print_r($response_array);exit;
                        //Check success response
                        if ($http_status === 200 and isset($response_array['error']) === false) {
                            $success = true;
                        } else {
                            $success = false;
                            if (!empty($response_array['error']['code'])) {
                                $error = $response_array['error']['code'].':'.$response_array['error']['description'];
                            } else {
                                $error = 'RAZORPAY_ERROR:Invalid Response <br/>'.$result;
                            }
                        }
                }
                //close connection
                curl_close($ch);
            } catch (Exception $e) {
                $success = false;
                $error = 'OPENCART_ERROR:Request to Razorpay Failed';
            }
            if ($success === true) {
                if(!empty($this->session->userdata('ci_subscription_keys'))) {
                    $this->session->unset_userdata('ci_subscription_keys');
                 }
                if (!$order_info['order_status_id']) {
                    redirect($this->input->post('merchant_surl_id'));
                } else {
                    redirect($this->input->post('merchant_surl_id'));
                }

            } else {
                redirect($this->input->post('merchant_furl_id'));
            }
        } else {
            echo 'An error occured. Contact site administrator, please!';
        }
    } 
	
	 public function cancel_order() {
		
		$this->config_model->delete_order_products($_SESSION['order_id']);
        redirect(site_url('home/mycart'));
    }
    public function success() {
        $data['title'] = 'Razorpay Success ';
		$data['scontent'] = array();
		$data['scontent']['user_id'] = $_SESSION['uid'];
		$this->config_model->delete_cart($data['scontent']);
		$arrProduct = $this->config_model->get_order_product_details($_SESSION['order_id']);
		
		$data['scontent'] = array();
		$data['scontent']['user_id'] = $_SESSION['uid'];
		$row = $this->config_model->get_user_details($data['scontent']);
		
		$smsdata= array();
		$msg = "Dear ".$_SESSION["name"]." your order has been placed successfully. We will shoot you a mail regarding your order details. Contact kojarmohammed555@gmail.com for any query.";
		$smsdata['msg']= urlencode($msg);
		$smsdata['mobile']= $row['mobile'];
		$this->crm_event->sendsms($smsdata);
		
		$emaildata = array();
		$emaildata['arrData']   = $arrProduct;
		$emaildata['to'] 		= $row['email'];
		$emaildata['subject']   = 'Order Confirmation Kojar Store';
		$emaildata['from']  	= 'dontreply@store.com';
		$emaildata['template']  = 'order_email';
		$this->crm_event->sendmail($emaildata);
        $this->load->view('payment_success', $data);
    }  
    public function failed() {
        $data['title'] = 'Razorpay Failed';            
        $this->load->view('razorpay/failed', $data);
    }  
}
?>