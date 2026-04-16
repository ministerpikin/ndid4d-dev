<?php
	if( isset( $_POST["email"] ) && $_POST["email"] && isset( $_POST["message"] ) && $_POST["message"] ){
		$pagepointer = './';
		$display_pagepointer = '';
		require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::db_connect();
		// require_once $pagepointer."settings/Config.php";
		require_once $pagepointer."settings/Setup.php";
		
		//echo convert_date_to_timestamp("12/03/1988"); EXIT;
		//echo date("d-M-Y", 574158292 ); exit;
		
		$settings = array(
			"subject" => $_POST["message"],
			"message" => $_POST["message"],
			"recipient_emails" => array( $_POST["email"] ),
			"recipient_fullnames" => array( $_POST["email"] ),
			"debug" => 2,
		);
		print_r(  send_mail( $settings ) );
	}else{
		?>
		<form method="post" action="">
			<label>Email</label>
			<input type="email" value="tracepatrick81@gmail.com" required name="email" />
			<hr />
			<label>Message</label>
			<input type="text" value="<?php echo 'test ' . date("d-M-Y H:i"); ?>" required name="message" />
			<hr />
			<input type="submit" value="Submit" />
		</form>
		<?php
	}
	//http://arch02-hp:819/feyi/engine/test.php
?>