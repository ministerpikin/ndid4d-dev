<?php
	$project = get_project_data();
	$project_title = "";
	if( isset( $project['project_title'] ) )$project_title = $project['project_title'];
	
	$width = "";
	if( ! ( isset( $data["skip_style"] ) && $data["skip_style"] ) ){
		include "email-style.php";
		//$width = "550px";
		$width = "650px";
	}
	if( ( isset( $data["width"] ) && $data["width"] ) ){
		$width = $data["width"];
	}
	
	$custom_heading = isset( $data["custom_heading"] )?$data["custom_heading"]:'';
	$show_heading = 1;
	$show_date = 1;
	$show_title = 1;
	
	if( isset( $data["mail_type"] ) && $data["mail_type"] ){
		switch( $data["mail_type"] ){
		case 6:
			$show_date = 0;
		case 5:
			$show_title = 0;
		break;
		case 7:
			$show_title = 0;
			$show_date = 0;
			$show_heading = 0;
		break;
		}
	}
	
	if( $custom_heading ){
		$show_heading = 0;
	}
?>
<style type="text/css">
.textdark { 
color: #444444;
font-family: Open Sans;
font-size: 13px;
line-height: 150%;
text-align: left;
}
</style>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#dee0e2; padding-top:20px; padding-bottom:20px;">
	<tr>
		<td>
			<center>
				<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" style="height:100%; background-color:#f9f9f9; border-bottom:1px solid #d6e9c6;  box-shadow:2px 2px 2px #ddd;">
					<?php if( $show_heading ){ ?>
					<tr style="background-color:#161b2d; color:#fff;">
						<td valign="top" style="text-align:center; padding:20px;">
						<strong><?php //if( isset( $project['project_title'] ) )echo $project['project_title']; ?></strong>
						<?php
						if( isset( $data["merchant_details"] ) && is_array( $data["merchant_details"] ) ){
							$u = $data["merchant_details"];
							$key = "photograph"; 
							if( isset( $u[$key] ) ){
								$default_logo = $u[$key]; 
							}
							$key = "phonenumber"; 
							if( isset( $u[$key] ) ){
								$phone = $u[$key]; 
							}
							$key = "email"; 
							if( isset( $u[$key] ) ){
								$email = $u[$key]; 
							}
							$key = "street_address"; 
							if( isset( $u[$key] ) ){
								$addr = $u[$key]; 
							}
							$key = "company_name"; 
							if( isset( $u[$key] ) ){
								$company = $u[$key]; 
							}
							
							$country = ""; $tcountry = ""; $key = "country"; if( isset( $u[$key] ) ){ $tcountry = get_select_option_value( array( 'id' => $u[$key], 'function_name' => 'get_countries' ) ); $country = $u[$key]; }
							?>
							<p><small><?php echo $company; ?><br /><span class="muted"><?php echo $addr; ?></span><small style="line-height:14px; display:block; font-size:0.8em;"><?php echo $phone; ?> | <?php echo $email; ?></small><small style="display:block; font-size:0.8em; line-height:12px;"><?php echo $tcountry; ?></small></small></p>
							<?php
						}else{
							//echo '<img src="' . $project["domain_name"]. 'frontend-assets/img/logo-b.png" style="max-height:60px; float:left; margin-right:10px;" align="left" />';
							
							echo "<strong style='line-height:60px; font-size:22px;'>".$project_title."</strong>";
						}
						?>
						
						</td>
					</tr>
					<?php }
					if( $custom_heading ){
						echo '<tr><td>'. $custom_heading .'</td></tr>';
					} ?>
					
					<?php if( $show_date ){ ?>
					<tr>
						<td style="text-align:right; padding:20px;"><?php echo date("jS F, Y"); ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td valign="top" style="padding:20px; font-size: 16px;">
							
							<?php if( $show_title ){ ?>
								<?php if( isset( $data["full_name"] ) && $data["full_name"] ){ ?>
								<strong>Dear <?php echo $data["full_name"]; ?>,</strong>
								<?php } ?>
								<h4 style="text-align:center;">
									<strong><?php $key = "title"; if( isset( $data[ $key ] ) && $data[ $key ] )echo $data[ $key ]; ?></strong>
								</h4>
								<br />
								<hr />
								<?php $key = "info"; if( isset( $data[ $key ] ) && $data[ $key ] ){ ?>
								<div class="textdark">
									<strong><?php echo $data[ $key ]; ?></strong>
								</div>
								<hr />
								<br />
								<?php } ?>
							<?php } ?>
							<div class="textdark">
							<?php 
								if( isset( $data["mail_type"] ) && $data["mail_type"] ){ //recommendation 
									switch( $data["mail_type"] ){
									case 3:
							?>
							<p><a target="_blank" href="<?php echo $site_url; ?>?page=verify-email-address&data=<?php if( isset( $data["id"] ) )echo $data["id"]; ?>" title="Verify your <?php echo $project_title; ?> email" class="btn red"><?php echo $site_url; ?>?page=verify-email-address&data=<?php if( isset( $data["id"] ) )echo $data["id"]; ?></a></p>
							<br />
							<?php 
									
									break;
									case 4:
							?>
							<p>Click this link to Login to your <?php echo $project_title; ?> Account and View your Invoice Details<br /><br /><a target="_blank" href="<?php echo $site_url; ?>?page=login" title="LOGIN TO YOUR <?php echo $project_title; ?> ACCOUNT" class="btn red">LOGIN TO YOUR <?php echo $project_title; ?> ACCOUNT</a></p>
							<br />
							<?php 
									}
								}
								
								if( isset( $data["html"] ) )echo $data["html"];
							?>
							</div><br />
						</td>
					</tr>
					<tr style="background-color:#f5f5f5;">
						<td style="padding:20px; text-align:center; font-size:12px;">
							<?php echo date("Y ") . $project_title; ?>. ALL Rights Reserved
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>