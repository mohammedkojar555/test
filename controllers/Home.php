<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('config_model');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	}
	
	public function index()
	{
		$data = array();
		$this->load->view('index',$data);
	}
	
	public function register()
	{
		$f_name = $_POST["f_name"];
		$l_name = $_POST["l_name"];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$mobile = $_POST['mobile'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$name = "/^[a-zA-Z ]+$/";
		$emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
		$number = "/^[0-9]+$/";
		if(empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) ||
		empty($mobile) || empty($address1) || empty($address2)){
			
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>PLease Fill all fields..!</b>
				</div>
			";
			exit();
		} else {
			if(!preg_match($name,$f_name)){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>this $f_name is not valid..!</b>
				</div>
			";
			exit();
		}
		if(!preg_match($name,$l_name)){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>this $l_name is not valid..!</b>
				</div>
			";
			exit();
		}
		if(!preg_match($emailValidation,$email)){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>this $email is not valid..!</b>
				</div>
			";
			exit();
		}
		if(strlen($password) < 9 ){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Password is weak</b>
				</div>
			";
			exit();
		}
		if(strlen($repassword) < 9 ){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Password is weak</b>
				</div>
			";
			exit();
		}
		if($password != $repassword){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>password is not same</b>
				</div>
			";
		}
		if(!preg_match($number,$mobile)){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Mobile number $mobile is not valid</b>
				</div>
			";
			exit();
		}
		if(!(strlen($mobile) == 10)){
			echo "
				<div class='alert alert-warning'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Mobile number must be 10 digit</b>
				</div>
			";
			exit();
		}
		$data['scontent'] = array();
		$data['scontent']['email'] = $email;
		$arrUser = $this->config_model->get_user_details($data['scontent']);
		if(!empty($arrUser))
		{
			echo "
				<div class='alert alert-danger'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Email Address is already available Try Another email address</b>
				</div>
			";
			exit();
		} 
		else 
		{
			$arrData = array();
			$arrData['first_name'] = $f_name;
			$arrData['last_name'] = $l_name;
			$arrData['email'] = $email;
			$arrData['password'] = $password;
			$arrData['mobile'] = $mobile;
			$arrData['address1'] = $address1;
			$arrData['address2'] = $address2;
			$user_id = $this->config_model->add_user($arrData);
			
			$_SESSION["uid"] = $user_id;
			$_SESSION["name"] = $f_name;
			$ip_add = getenv("REMOTE_ADDR");
			
			$arrUpdate = array();
			$arrUpdate['user_id'] = $user_id;
			$this->config_model->update_cart_by_register($arrUpdate,$ip_add);
			
			echo "register_success";
			echo "<script> location.href='".site_url('')."'; </script>";
			exit;
		}
	  }
		
	}
	
	public function login()
	{
		if(isset($_POST["email"]) && isset($_POST["password"]))
		{
			$email = $_POST["email"];
			$password = $_POST["password"];
			
			$data['scontent'] = array();
			$data['scontent']['email'] = $email;
			$data['scontent']['password'] = $password;
			$arrUser = $this->config_model->get_user_details($data['scontent']);
			
			if(!empty($arrUser))
			{
				$_SESSION["uid"] = $arrUser["user_id"];
			    $_SESSION["name"] = $arrUser["first_name"];
				$ip_add = getenv("REMOTE_ADDR");
				
				if (isset($_COOKIE["product_list"])) 
				{
					echo "cart_login";
					exit();
				}
				echo "login_success";
				exit();
			}
			else
			{
					echo "<span style='color:red;'>Please register before login..!</span>";
                    exit();
			}
		}
	}
	
	public function addtocart()
	{
	  $ip_add = getenv("REMOTE_ADDR");
	  
	  if(isset($_POST["addToCart"]))
	  {
		$p_id = $_POST["proId"];
		
		if(isset($_SESSION["uid"]))
		{

			$user_id = $_SESSION["uid"];
			
			$data['scontent'] 				= array();
			$data['scontent']['p_id'] 		= $p_id;
			$data['scontent']['user_id'] 	= $user_id;
			
			$arrCart = $this->config_model->get_cart_by_id($data['scontent']);
			if(!empty($arrCart))
			{
				echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>
				";
			}
			else
			{
				$arrData = array();
				$arrData['p_id'] = $p_id;
				$arrData['ip_add'] = $ip_add;
				$arrData['user_id'] = $user_id;
				$arrData['qty'] = 1;
				$fldi_id = $this->config_model->insert_cart($arrData);
				echo "
						<div class='alert alert-success'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is Added..!</b>
						</div>
					";
			}
		
		}
		else
		{
			
			$data['scontent'] 				= array();
			$data['scontent']['ip_add'] 	= $ip_add;
			$data['scontent']['p_id'] 		= $p_id;
			
			
			$arrCart = $this->config_model->get_cart_by_id($data['scontent']);
			
			if(!empty($arrCart))
			{
				echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
					exit();
			}
			else
			{
				$arrData = array();
				$arrData['p_id'] = $p_id;
				$arrData['ip_add'] = $ip_add;
				$arrData['user_id'] = 0;
				$arrData['qty'] = 1;
				$fldi_id = $this->config_model->insert_cart($arrData);
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
				exit();
			}
			
			
		}
	   }
	   
	   if (isset($_POST["count_item"])) 
	   {
			if (isset($_SESSION["uid"])) 
			{
				$data['scontent'] 				= array();
				$data['scontent']['user_id'] 	= $user_id;
				$count = $this->config_model->get_cart_count($data['scontent']);
			}
			else
			{
				$data['scontent'] 				= array();
				$data['scontent']['ip_add'] 	= $ip_add;
				$count = $this->config_model->get_cart_count($data['scontent']);
			}
			echo $count;
			exit();
		}
		
		//Get Cart Item From Database to Dropdown menu
		if (isset($_POST["Common"])) 
		{

			if (isset($_SESSION["uid"])) 
			{
				
				//When user is logged in this query will execute
				$data['scontent'] 				= array();
				$data['scontent']['user_id'] 	= $user_id;
				$arrProduct = $this->config_model->get_cart_product_details($data['scontent']);
			}
			else
			{
				//When user is not logged in this query will execute
				$data['scontent'] 				= array();
				$data['scontent']['ip_add'] 	= $ip_add;
				$arrProduct = $this->config_model->get_cart_product_details($data['scontent']);
			}
			if (isset($_POST["getCartItem"])) 
			{
				if(is_array($arrProduct) && count($arrProduct) > 0)
				{
					$n=0;
					$total_price=0;
					foreach ($arrProduct as $row)
					{
						$n++;
						$product_id = $row["product_id"];
						$product_title = $row["product_title"];
						$product_price = $row["product_price"];
						$product_image = site_url('assets/product_images/'.$row['product_image']);
						$cart_item_id = $row["id"];
						$qty = $row["qty"];
						$total_price=$total_price+$product_price;
						echo '
							
							
							<div class="product-widget">
														<div class="product-img">
															<img src="'.$product_image.'" alt="">
														</div>
														<div class="product-body">
															<h3 class="product-name"><a href="#">'.$product_title.'</a></h3>
															<h4 class="product-price"><span class="qty">'.$n.'</span>&#8377;'.$product_price.'</h4>
														</div>
														
													</div>'
							
							
							;
						
					}
					
					echo '<div class="cart-summary">
							<small class="qty">'.$n.' Item(s) selected</small>
							<h5>&#8377;'.$total_price.'</h5>
						</div>'
					?>
						
						
					<?php
					
					exit();
					}
				}
			
			
			
			if (isset($_POST["checkOutDetails"])) {
				if(!empty($arrProduct))
				{
					echo '<div class="main ">
					<div class="table-responsive">
					<form method="post" action="login_form.php">
					
						   <table id="cart" class="table table-hover table-condensed" id="">
							<thead>
								<tr>
									<th style="width:50%">Product</th>
									<th style="width:10%">Price</th>
									<th style="width:8%">Quantity</th>
									<th style="width:7%" class="text-center">Subtotal</th>
									<th style="width:10%"></th>
								</tr>
							</thead>
							<tbody>
							';
							$n=0;
					foreach ($arrProduct as $row)
					{
							$n++;
							$product_id = $row["product_id"];
							$product_title = $row["product_title"];
							$product_price = $row["product_price"];
							$product_image = site_url('assets/product_images/'.$row['product_image']);
							$cart_item_id = $row["id"];
							$qty = $row["qty"];
							echo 
								'
									 
								<tr>
									<td data-th="Product" >
										<div class="row">
										
											<div class="col-sm-4 "><img src="'.$product_image.'" style="height: 70px;width:75px;"/>
											<h4 class="nomargin product-name header-cart-item-name"><a href="product.php?p='.$product_id.'">'.$product_title.'</a></h4>
											</div>
											<div class="col-sm-6">
												<div style="max-width=50px;">
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p>
												</div>
											</div>
											
											
										</div>
									</td>
									<input type="hidden" name="product_id[]" value="'.$product_id.'"/>
									<input type="hidden" name="" value="'.$cart_item_id.'"/>
									<td data-th="Price"><input type="text" class="form-control price" value="'.$product_price.'" readonly="readonly"></td>
									<td data-th="Quantity">
										<input type="text" class="form-control qty" value="'.$qty.'" >
									</td>
									<td data-th="Subtotal" class="text-center"><input type="text" class="form-control total" value="'.$product_price.'" readonly="readonly"></td>
									<td class="actions" data-th="">
									<div class="btn-group">
										<a href="#" class="btn btn-info btn-sm update" update_id="'.$product_id.'"><i class="fa fa-refresh"></i></a>
										
										<a href="#" class="btn btn-danger btn-sm remove" remove_id="'.$product_id.'"><i class="fa fa-trash-o"></i></a>		
									</div>							
									</td>
								</tr>
							
									
									';
						}
						
						echo '</tbody>
						<tfoot>
							
							<tr>
								<td><a href="'.site_url('').'" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
								<td colspan="2" class="hidden-xs"></td>
								<td class="hidden-xs text-center"><b class="net_total" ></b></td>
								<div id="issessionset"></div>
								<td>
									
									';
						if (!isset($_SESSION["uid"])) {
							echo '
							
									<a href="" data-toggle="modal" data-target="#Modal_register" class="btn btn-success">Ready to Checkout</a></td>
										</tr>
									</tfoot>
						
									</table></div></div>';
						}else if(isset($_SESSION["uid"])){
							//Paypal checkout form
							echo '
							</form>
							
								<form action="'.site_url('razorpay/checkout').'" method="post">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="business" value="shoppingcart@puneeth.com">
									<input type="hidden" name="upload" value="1">';
									  
									$x=0;
									$data['scontent'] 				= array();
									$data['scontent']['user_id'] 	= $user_id;
									$arrProduct = $this->config_model->get_cart_product_details($data['scontent']);
									
									if(is_array($arrProduct) && count($arrProduct) > 0)
									{
										foreach ($arrProduct as $row)
										{
											$x++;
											echo '<input type="hidden" name="total_count" value="'.$x.'">
											<input type="hidden" name="item_name_'.$x.'" value="'.$row["product_title"].'">
											 <input type="hidden" name="item_number_'.$x.'" value="'.$x.'">
											 <input type="hidden" name="amount_'.$x.'" value="'.$row["product_price"].'">
											 <input type="hidden" name="quantity_'.$x.'" value="'.$row["qty"].'">';
										}
									}
									
									  
									echo   
										'<input type="hidden" name="return" value="http://localhost/myfiles/public_html/payment_success.php"/>
											<input type="hidden" name="notify_url" value="http://localhost/myfiles/public_html/payment_success.php">
											<input type="hidden" name="cancel_return" value="http://localhost/myfiles/public_html/cancel.php"/>
											<input type="hidden" name="currency_code" value="USD"/>
											<input type="hidden" name="custom" value="'.$_SESSION["uid"].'"/>
											<input type="submit" id="submit" name="login_user_with_product" name="submit" class="btn btn-success" value="Ready to Checkout">
											</form></td>
											
											</tr>
											
											</tfoot>
											
									</table></div></div>    
										';
						}
					}
				}
			
			
		}
		
		//Remove Item From cart
		if (isset($_POST["removeItemFromCart"])) 
		{
			$remove_id = $_POST["rid"];
			if (isset($_SESSION["uid"])) 
			{
				$data['scontent'] 			 = array();
				$data['scontent']['p_id'] 	 = $remove_id;
				$data['scontent']['user_id'] = $_SESSION["uid"];
				$this->config_model->delete_cart($data['scontent']);
			}
			else
			{
				$data['scontent'] 			 = array();
				$data['scontent']['p_id'] 	 = $remove_id;
				$data['scontent']['ip_add']  = $ip_add;
				$this->config_model->delete_cart($data['scontent']);
			}
			 echo "<div class='alert alert-danger'>
								<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
								<b>Product is removed from cart</b>
						</div>";
			 exit();
		}


		//Update Item From cart
		if (isset($_POST["updateCartItem"])) 
		{
			$update_id = $_POST["update_id"];
			$qty = $_POST["qty"];
			if (isset($_SESSION["uid"])) 
			{
				$arrUpdate 		  = array();
				$arrUpdate['qty'] = $qty;
				$this->config_model->update_cart_by_id($arrUpdate,$_SESSION["uid"],$update_id);
			}
			else
			{
				$arrUpdate 		  = array();
				$arrUpdate['qty'] = $qty;
				$this->config_model->update_cart_by_id($arrUpdate,$ip_add,$update_id);
			}
		
				echo "<div class='alert alert-info'>
								<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
								<b>Product is updated</b>
						</div>";
				exit();
		}
	}
	
	public function mycart()
	{
		$data = array();
		$this->load->view('cart',$data);
		
	}
	
	public function checkout()
	{
		$data = array();
		$this->load->view('checkout',$data);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		
		redirect(site_url(''), 'location');
	}
	
}	