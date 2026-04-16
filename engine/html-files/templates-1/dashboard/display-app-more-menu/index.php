<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$super = 0;
	
	$access = array();
	if( isset( $user_info["user_privilege"] ) && $user_info["user_privilege"] ){
		if( $user_info["user_privilege"] == "1300130013" ){
			$super = 1;
		}else{
			$functions = get_access_roles_details( array( "id" => $user_info["user_privilege"] ) );
			if( isset( $functions[ $user_info["user_privilege"] ]["accessible_functions"] ) ){
				$a = explode( ":::" , $functions[ $user_info["user_privilege"] ]["accessible_functions"] );
				if( is_array( $a ) && $a ){
					foreach( $a as $k => $v ){
						$access[ $v ] = $v;
					}
				}
			}
		}
	}
			
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$ver = get_application_version( $pagepointer );
	
	$package = "";
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
	}
	
	$show_income_expenditure = 1;
	$app_title = "HYELLA";
	$app_category = "Business Management System";
	
	switch( $package ){
	case "hotel":
		$show_income_expenditure = 0;
		$app_category = "Hotel Management System";
	break;
	case "jewelry":
		$app_category = "Jewelry Management System";
	break;
	}
?>
<!--EXCEL IMPOT FORM-->
<div class="row" >
	
	<div class="col-md-6" id="expense-view"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<div class="portlet-body1" style="max-height:500px; overflow-y:auto; overflow-x:hidden; background:transparent !important;">
				<h2 style="color:#fff;">Reports</h2>
				<hr />
				
				<div class="tiles">
					<?php
						$key = "10859437149";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_sales_report" function-class="sales" function-id="More: Manage Sales">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 Manage Sales
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<?php
						$key = "10858963073";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_reports_full_view" function-class="sales" function-id="More: Sales Reports">
					<div class="tile bg-dark">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 Sales Reports
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<a href="#" class="custom-action-button" function-name="display_all_receivables_full_view" function-class="transactions" function-id="More: Customer Transactions">
						<div class="tile bg-dark1">
						   <div class="tile-body">
							  <i class="icon-user"></i>
						   </div>
						   <div class="tile-object">
							  <div class="name">
								 Customer Transactions
							  </div>
						   </div>
						</div>
					</a>
					
					<?php
						$key = "10858965439";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_reports_full_view" function-class="inventory" function-id="More: Stock Levels Reports">
					<div class="tile bg-dark">
					   <div class="tile-body">
						  <i class="icon-reorder"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 Stock Levels
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<?php if( $package == "accounting" || $package == "jewelry" ){ ?>
					
					<?php
						$key = "11209887664";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_all_reports_full_view" function-class="transactions" function-id="More: Financial Reports">
						<div class="tile bg-dark">
						   <div class="tile-body">
							  <i class="icon-bar-chart"></i>
						   </div>
						   <div class="tile-object">
							  <div class="name">
								 Financial Reports
							  </div>
						   </div>
						</div>
					</a>
					<?php } ?>
					
					<?php }else{ ?>
						<?php
							
							$key = "10859455645";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<a href="#" class="custom-action-button" function-name="display_app_production_report" function-class="production" function-id="More: Production Reports">
						<div class="tile bg-dark1">
						   <div class="tile-body">
							  <i class="icon-reorder"></i>
						   </div>
						   <div class="tile-object">
							  <div class="name">
								 Production Reports
							  </div>
						   </div>
						</div>
						</a>
						<?php } ?>
						
					<?php } ?>
					
					<?php if( $package == "hotel"  ){ ?>
					<a href="#" class="custom-action-button" function-name="display_my_transaction_reports_full_view" function-class="hotel_checkin" function-id="More: My Transactions">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-bar-chart"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 My Transactions
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					
					<?php if( $package == "jewelry" ){ ?>
					<a href="#" class="custom-action-button" function-name="display_product_catalog" function-class="inventory" function-id="More: Product Catalogue">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class=" icon-th"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 Product Catalogue
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<!--
					<a href="#" class="custom-action-button" function-name="display_app_view" function-class="vendor" function-id="More: Vendors Reports">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-truck"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							Vendors
						  </div>
					   </div>
					</div>
					</a>
					-->
					
				 </div>
				
			</div>
		</div>
	</div>
	
	<div class="col-md-6"> 
		
		<div style="background:transparent !important; border:none !important;" class="grey box portlet <?php if( ! ( isset( $data['mobile_enabled'] ) && $data['mobile_enabled'] ) ){ ?>grey box<?php } ?>">
			<h2 style="color:#fff;">More Options</h2>
				<hr />
				<div class="tiles">
					<?php
						//$key = "11297443254"; //visible backend
						$key = "10859442935"; //backend
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#">
					<?php }else{ ?>
					<a href="#" >
					<?php } ?>
					
					<div class="tile double bg-dark">
					   <div class="tile-body">
						  <br />
						  <img style="margin-top:-10px;" src="<?php echo $site_url; ?>frontend-assets/img/logo_blue.png" alt="">
						  <h3><?php echo $app_title; ?></h3>
						  <p>
							 <?php echo $app_category; ?>
						  </p>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 
						  </div>
						  <div class="number">
							 ver <?php echo $ver; ?>
						  </div>
					   </div>
					</div>
					</a>
					
					
					<?php
						/*
						$key = "10858982656";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_view" function-class="category" function-id="More: Manage Category">
					<div class="tile">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 New Category
						  </div>
					   </div>
					</div>
					</a>
					<?php } */ ?>
					
					<?php 
					$key = "10859461627";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="post_new_transaction" function-class="transactions" function-id="More: Post Financial Transactions">
					<div class="tile bg-purple">
					   <div class="tile-body">
						  <i class="icon-usd"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							Financial Transactions
						  </div>
					   </div>
					</div>
					</a>
					<!--
					<a href="#" class="custom-action-button1 loadProductionManifest" onclick="$.fn.pHost.loadProductionManifest(); return false;" function-name="display_app_view" function-class="stores" function-id="More: Create Production Manifest">
					<div class="tile bg-purple">
					   <div class="tile-body">
						  <i class="icon-cogs"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							Production
						  </div>
					   </div>
					</div>
					</a>
					-->
					<?php } ?>
						
					<?php
						$key = "10858988569";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_view_manage" function-nameOLD="display_app_view" function-class="vendors" function-id="More: Manage Vendors">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							All Vendors
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<?php
						$key = "10859007954";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_view" function-class="users" function-id="More: Manage Employees">
					<div class="tile bg-dark">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 New Employee
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<!--
					<a href="#" class="custom-action-button" function-name="display_app_view" function-class="stores" function-id="More: Manage Stores">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-shopping-cart"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							 New Store
						  </div>
					   </div>
					</div>
					</a>
					-->
				 </div>
				
					<?php
						$key = "10858983830";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" class="custom-action-button" function-name="display_app_view_manage" function-class="customers" function-id="More: Manage Customers">
					<div class="tile bg-dark1">
					   <div class="tile-body">
						  <i class="icon-calendar"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							All Customers
						  </div>
					   </div>
					</div>
					</a>
					<?php } ?>
					
					<?php
						/*
						$key = "11297398525";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<a href="#" onclick="$.fn.pHost.loadExpenses(); return false;" class="custom-action-button1 loadExpenses" function-name="display_record_payment" function-class="cart" function-id="More: Record Payment">
					<div class="tile bg-purple">
					   <div class="tile-body">
						  <i class="icon-usd"></i>
					   </div>
					   <div class="tile-object">
						  <div class="name">
							Manage Expenses
						  </div>
					   </div>
					</div>
					</a>
					<?php } */ ?>
					
				 </div>
				
		</div>

	</div>
	
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>