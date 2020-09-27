<?php
$this->load->view('header');
?>
<br/>
<div class="row">
    <div class="col-lg-12">
        <h2>Razorpay Payment Gateway Integration In Codeigniter using cURL</h2>                 
    </div>
</div><!-- /.row -->
<?php
$productinfo = 'Hi this is test product info';
$txnid = time();
$surl = $surl;
$furl = $furl;
$key_id = 'rzp_test_8zYHHCbyDTgz0y';
//$key_secret = 'PaNzFBDpiEjqJhgEsq0EMOyD';
$currency_code = $currency_code;            
$total = $total*100; 
$amount = $total;
$merchant_order_id = $order_id;
$card_holder_name = 'Mohammed Kojar';
$email = 'kojarmohammed555@gmail.com';
$phone = '8369262057';
$name = 'Test Ecommerce';
$return_url = site_url().'razorpay/callback';
?>
<div class="row">
    <div class="col-lg-12">
        <?php if(!empty($this->session->flashdata('msg'))){ ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('msg'); ?>
            </div>        
        <?php } ?>
        <?php if(validation_errors()) { ?>
          <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
          </div>
        <?php } ?>
    </div>
</div>
 <form name="razorpay-form" id="razorpay-form" action="<?php echo $return_url; ?>" method="POST">
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
  <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?php echo $merchant_order_id; ?>"/>
  <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="<?php echo $txnid; ?>"/>
  <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="<?php echo $productinfo; ?>"/>
  <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="<?php echo $surl; ?>"/>
  <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="<?php echo $furl; ?>"/>
  <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="<?php echo $card_holder_name; ?>"/>
  <input type="hidden" name="merchant_total" id="merchant_total" value="<?php echo $total; ?>"/>
  <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?php echo $amount; ?>"/>
</form>
    <div class="row">   
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
            <table class="table table-hover table-condensed" style="">
                <thead class="bg-primary">
                    <tr>
                        <th width="15%" class="text-left" style="vertical-align: inherit">Image</th>
                        <th width="30%" class="text-left" style="vertical-align: inherit">Name</th>
                        <th width="15%" class="text-right" style="vertical-align: inherit">Qty</th>
                        <th width="15%" class="text-right" style="vertical-align: inherit">Price</th>                        
                    </tr>
                </thead>                        
                <tbody>
					<?php 
					 if(is_array($arrProduct) && count($arrProduct)>0)
					 {
						 foreach ($arrProduct as $product)
						 {
							 ?>
							 <tr>
								<td class="text-left"><img width="80" height="80" src="<?php echo site_url('assets/product_images/'.$product['product_image']);?>"></td>
								<td class="text-left"><?php echo $product['product_title'];?></td>
								<td class="text-right"><?php echo $product['qty'];?></td>
								<td class="text-right"><?php print $product['amt'];?></td>                        
							</tr>
					<?php
						 }
					 }
					?>
                                            
                </tbody>                        
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-right">
            <a href="<?php echo site_url('razorpay/cancel_order');?>" name="reset_add_emp" id="re-submit-emp" class="btn btn-warning"><i class="fa fa-mail-reply"></i> Back</a>
            <input  id="submit-pay" type="submit" onclick="razorpaySubmit(this);" value="Pay Now" class="btn btn-primary" />
        </div>
    </div>

<br/>
<?php
$this->load->view('footer');
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var razorpay_options = {
    key: "<?php echo $key_id; ?>",
    amount: "<?php echo $total; ?>",
    name: "<?php echo $name; ?>",
    description: "Order # <?php echo $merchant_order_id; ?>",
    netbanking: true,
    currency: "<?php echo $currency_code; ?>",
    prefill: {
      name:"<?php echo $card_holder_name; ?>",
      email: "<?php echo $email; ?>",
      contact: "<?php echo $phone; ?>"
    },
    notes: {
      soolegal_order_id: "<?php echo $merchant_order_id; ?>",
    },
    handler: function (transaction) {
        document.getElementById('razorpay_payment_id').value = transaction.razorpay_payment_id;
        document.getElementById('razorpay-form').submit();
    },
    "modal": {
        "ondismiss": function(){
            location.reload()
        }
    }
  };
  var razorpay_submit_btn, razorpay_instance;

  function razorpaySubmit(el){
    if(typeof Razorpay == 'undefined'){
      setTimeout(razorpaySubmit, 200);
      if(!razorpay_submit_btn && el){
        razorpay_submit_btn = el;
        el.disabled = true;
        el.value = 'Please wait...';  
      }
    } else {
      if(!razorpay_instance){
        razorpay_instance = new Razorpay(razorpay_options);
        if(razorpay_submit_btn){
          razorpay_submit_btn.disabled = false;
          razorpay_submit_btn.value = "Pay Now";
        }
      }
      razorpay_instance.open();
    }
  }  
</script>