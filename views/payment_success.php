					<!DOCTYPE html>
					<html>
						<head>
							<meta charset="UTF-8">
							<title>My Store</title>
							<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css');?>"/>
							<script src="<?php echo site_url('assets/js/jquery2.js');?>"></script>
							<script src="<?php echo site_url('assets/js/bootstrap.min.js');?>"></script>
							<script src="<?php echo site_url('assets/main.js');?>"></script>
							<style>
								table tr td {padding:10px;}
							</style>
						</head>
					<body>
						<div class="navbar navbar-inverse navbar-fixed-top">
							<div class="container-fluid">	
								<div class="navbar-header">
									<a href="#" class="navbar-brand">Online Store</a>
								</div>
								<ul class="nav navbar-nav">
									<li><a href="<?php echo site_url(''); ?>"><span class="glyphicon glyphicon-home"></span>Home</a></li>
									<li><a href="#"><span class="glyphicon glyphicon-modal-window"></span>Product</a></li>
								</ul>
							</div>
						</div>
						<p><br/></p>
						<p><br/></p>
						<p><br/></p>
						<div class="container-fluid">
						
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div class="panel panel-default">
										<div class="panel-heading"></div>
										<div class="panel-body">
											<h1>Thankyou </h1>
											<hr/>
											<p>Hello <?php echo "<b>".$_SESSION["name"]."</b>"; ?>,Your payment process is 
											successfully completed and your Transaction id is <b><?php echo rand(10000000,99999999); ?></b><br/>
											you can continue your Shopping <br/></p>
											<a href="<?php echo site_url(''); ?>" class="btn btn-success btn-lg">Continue Shopping</a>
										</div>
										<div class="panel-footer"></div>
									</div>
								</div>
								<div class="col-md-2"></div>
							</div>
						</div>
					</body>
					</html>


















































