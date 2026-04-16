<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	// echo '<pre>';print_r( $data );echo '</pre>';
$user = isset( $data[ 'user' ] ) ? $data[ 'user' ] : [];
$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
$fname = isset( $user[ 'firstname' ] ) ? $user[ 'firstname' ] : '';
$lname = isset( $user[ 'lastname' ] ) ? $user[ 'lastname' ] : '';
$email = isset( $user[ 'email' ] ) ? $user[ 'email' ] : '';
$id = isset( $user[ 'id' ] ) ? $user[ 'id' ] : '';

$formParams = isset( $data[ 'fparams' ] ) ? $data[ 'fparams' ] : [];
$formParams[ 'id' ] = $id;
$formParams[ 'table' ] = $table;
?>
<h4>Welcome <b><?php echo $fname . ' ' . $lname; ?></b></h4>
<p>Please check your email to input otp below</p>
<form class="login-form activate-ajax login100-form validate-form" id="usersOTPForm" action="?action=users&todo=verify_otp&default=default" method="post">
<!-- <form action="https://themesbrand.com/velzon/html/material/index.html"> -->

	<textarea style="display: none;" class="form-control" name="formParams" ><?php echo json_encode( $formParams ); ?></textarea>

	<div class="mb-3">
		<div class="float-end">
			<a href="#" text="Click to resend OTP to <?php echo $email; ?>" id="otp-click-act" class="custom-single-selected-record-button text-muted" override-selected-record="<?php echo $id; ?>" action="?module=&action=users&todo=resend_otp&default=default">Resend OTP <span id="countdownTimer"></span></a>
		</div>
		
		<label for="username" class="form-label">OTP <sup class="r">*</sup></label>
		<input type="text" name="otp" class="form-control" required id="username" placeholder="Enter OTP">
	</div>
	
	<div class="mt-4">
		<button class="btn btn-success w-100" type="submit">Verify OTP</button>
	</div>
	
	
</form>

<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/otp-script.js' ) )include "otp-script.js"; ?>
</script>
</div>