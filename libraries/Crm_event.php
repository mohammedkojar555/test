<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crm_event
{
	public $CI;
	public function __construct()
	{
		$this->CI 			= &get_instance();
	}
	
	function sendmail($emailData = array())
    {
			  //echo "<pre>";print_r($emailData);exit;
					 $this->CI->load->library('email');

			$this->CI->email->initialize(array(
			'protocol' => 'smtp',
		    'smtp_host' => 'smtp.sendgrid.net',
		    'smtp_user' => '',
		    'smtp_pass' => '',
		    'smtp_port' => 587,
		    'crlf' => "\r\n",
		    'newline' => "\r\n",
		    'charset'=>'utf-8',
		    'wordwrap'=> TRUE,
		    'mailtype' => 'html'
	  ));
			  
		
		$data = array();
		$data['arrData'] = $emailData['arrData'];
		$message = $this->CI->load->view($emailData['template'],$data,true);
			
		$this->CI->email->from('donotreply@store.com', 'Kojar Store');
		$this->CI->email->to($emailData['to']);
		if(!empty($emailData['cc']))
		{
			$this->CI->email->cc($emailData['cc']);
		}
		if(!empty($emailData['subject']))
		{
			$this->CI->email->subject($emailData['subject']);
		}
		if(!empty($message))
		{
			$this->CI->email->message($message);
		}
		if(isset($emailData['attachment']) && !empty($emailData['attachment']))
		{
			$this->CI->email->attach($emailData['attachment']); 
		}
		$this->CI->email->send();
		/* echo $message;
		echo"<pre>"; print_r($this->CI->email->print_debugger());
		exit; */
 }
 
 function sendsms($smsdata = array())
	{
		$smsmessage = $smsdata['msg'];
		$dest		= $smsdata['mobile'];
		$str="https://www.fast2sms.com/dev/bulk?authorization=kd9ZO15832fq74bEpGLilt0CDJyNInTYRSAXuPjoVHzMwrBQm66xyPMjwE2CFdpkXa40L1AbuBnUQ7Wm&sender_id=FSTSMS&message=$smsmessage&language=english&route=p&numbers=$dest";
		$ans = file($str);
	}
	
	
	
}
?>