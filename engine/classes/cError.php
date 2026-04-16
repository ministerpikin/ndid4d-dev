<?php
	/**
	 * Error Class
	 *
	 * @used in  				Internally in throughout the Application
	 * @created  				10:52 | 15-07-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Error / Notification
	|--------------------------------------------------------------------------
	|
	| Creates and reports outcomes of actions.
	*/
	
	class cError{
		public $calling_page = '../';
		
		public $class_name = 'error';
		public $database_connection = '';
		public $database_name = '';
		public $action_to_perform = '';
		
		public $user = '';
		public $user_id = '';
		public $priv_id = '';
		
		private $error_code = '000000';
		public $error = array();
		public $regenerate_form_token = 0;
		
		public $user_full_name = 'unknown';
		public $user_mail = 'unknown';
		
		public $class_that_triggered_error = 'unknown';
		public $method_in_class_that_triggered_error = 'unknown';
		public $additional_details_of_error = 'none';
		public $error_title = '';
		
		public $html_format = 0;
		
		public $push_notification_key = 'hyella-push-notification';
		public $push_notification_data = array();
		
		private $record_id = '';
		private $form_action_todo = 'view';
		
		private $table = 'error';
		
		function __construct( $code = '' ){
			$this->error_code = strval( $code );
		}
		
		function error(){
			//INITIALIZE RETURN VALUE
			$returned_value = array();
			switch ($this->action_to_perform){
			case 'notify':
				$returned_value = $this->_notify();
			break;
			case 'push_notification':
				$returned_value = $this->_prepare_push_notification();
			break;
			}
			
			return $returned_value;
		}
		
		private function _notify(){
			$returning_html_data = 'Error Handler';
			
			//1. GET CODE
			$code = $this->error_code;
			$codes = $this->_get_code();
			
			//2. INTERPRETE CODE
			if( isset( $codes[$code] ) && is_array( $codes[$code] ) ){
				$this->error = $codes[$code];
			}else{
				//Invalid error code
				$this->error = $codes['000000'];
			}
			
			//3. INITIATE LOGGING
			/*
			if(isset($this->error['inf']))$this->error['inf'] = '';
			
			auditor($this->calling_page,$this->priv_id,$this->user_id,$this->user,$this->database_connection,$this->database_name,$this->error['typ'],'notice',$this->error['err'].' - '.$this->error['cls'].' - '.$this->error['mth'].' - '.$this->error['inf']);
			*/
			
			//4. RETURN MESSAGE
			$error = $this->error;
			
			if( $this->error_title ){
				$error['title'] = $this->error_title;
				$this->error['err'] = $this->error_title;
			}
			
			if( isset( $this->additional_details_of_error ) && $this->additional_details_of_error ){
				$this->error['inf'] = $this->additional_details_of_error;
			}

			$tversion = defined( 'HYELLA_THEME' ) && HYELLA_THEME ? HYELLA_THEME : 'v1';
			switch( $tversion ){
			case 'v3':
				$error['html'] = '<div class="alert ' . $this->error['theme'] . ' alert-block1 alert-dismissable">';
				$error['html'] .= ' <button type="button" class="close alert-close-button" data-dismiss="alert">&times;</button>';
			break;
			default:
				$error['html'] = '<div class="note note-danger alert alert-dismissable '.$this->error['theme'].'">';
					// $error['html'] .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
					$error['html'] .= ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			break;
			}
				if( $this->error['err'] )$error['html'] .= '<h3>'.$this->error['err'].'</h3>';
				if( isset( $this->error['inf'] ) ){
					if( $this->error['msg'] != $this->error['inf'] ){
						$error['html'] .= '<p>'.$this->error['inf'].'<br />'.$this->error['msg'].'</p>';
					}else{
						$error['html'] .= '<p>'.$this->error['inf'].'</p>';
					}
				}else{
					if( isset( $this->error['msg'] ) )$error['html'] .= '<p>'.$this->error['msg'].'</p>';
				}
			$error['html'] .= '</div>';
			
			//5. REGENERATE TOKEN
			$error['tok'] = '';
			if( isset($this->regenerate_form_token) ){
				$error['tok'] = generate_token( array( 'table' => $this->class_that_triggered_error ) );
			}
			
			return $error;
		}
		
		private function _prepare_push_notification(){
			$cache_key = $this->push_notification_key;
			$record = $this->push_notification_data;
			
			if( isset( $record["status"] ) && $record["status"] ){
				switch( $record["status"] ){
				case "load":
					$settings = array(
						'cache_key' => $cache_key,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$records = get_cache_for_special_values( $settings );
					if( ! is_array( $records ) )$records = array();
					
					$record['check_sum'] = date("U");
					$records[] = $record;
					
					$settings['cache_values'] = $records;
					set_cache_for_special_values( $settings );
				break;
				case "pull":
				case "pull_and_notify":
					$settings = array(
						'cache_key' => $cache_key,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$records = get_cache_for_special_values( $settings );
					if( ! is_array( $records ) )$records = array();
					
					if( ! empty( $records ) ){
						$push_notify = array();
						foreach( $records as $key => $record ){
							$push_notify = $record;
							break;
						}
						unset( $records[ $key ] );
						
						$settings['cache_values'] = $records;
						set_cache_for_special_values( $settings );
						
						switch( $record["status"] ){
						case "pull_and_notify":
							if( isset( $record["code"] ) && $record["html"] ){
								$this->error_code = $record["code"];
								$this->error["msg"] = $record["html"];
								return $this->error();
							}
						break;
						}
						
						return $push_notify;
					}
				break;
				}
				
			}
		}
		
		private function _get_code(){
			return array(
				/*------------*/
				//System Errors
				/*------------*/
				'000000' => array(
					'typ' => 'serror',
					'err' => 'Invalid Code',
					'msg' => 'The error code requested is invalid',
					'cls' => 'cError.php',
					'mth' => 'notify',
					'theme' => 'note note-danger alert-danger',
				),
				'000001' => array(
					'typ' => 'serror',
					'err' => 'SQL Error',
					'msg' => 'Something was wrong with the SQL Query',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000002' => array(
					'typ' => 'serror',
					'err' => 'Invalid Token',
					'msg' => 'Please ensure that the form was generated by the app',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000003' => array(
					'typ' => 'serror',
					'err' => 'Invalid Post Data',
					'msg' => 'The data sent to the app via the HTML header is invalid',
					'cls' => '-',
					'mth' => 'notify',
					'theme' => 'note note-danger alert-danger',
				),
				'000004' => array(
					'typ' => 'serror',
					'err' => 'Unset Session Key',
					'msg' => 'The application Session[key] variable was not set in settings.php',
					'cls' => '-',
					'mth' => 'notify',
					'theme' => 'note note-danger alert-danger',
				),
				'000005' => array(
					'typ' => 'serror',
					'err' => 'Unable to Authenticate Report',
					'msg' => 'Could not generate report key',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000006' => array(
					'typ' => 'serror',
					'err' => 'Unsuccessful Update Operation',
					'msg' => 'Record in the Database could not be updated by the query',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000007' => array(
					'typ' => 'serror',
					'err' => 'Record Creation Failed',
					'msg' => 'Sorry, the system was unable to create a new record',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000008' => array(
					'typ' => 'serror',
					'err' => 'PDF Report Creation Failed',
					'msg' => 'Sorry, the system was unable to create a new pdf report',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000009' => array(
					'typ' => 'serror',
					'err' => 'No HTML Data Sent for PDF Creation',
					'msg' => 'Sorry, the system was unable to create a new pdf report because no data was received',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000010' => array(
					'typ' => 'serror',
					'err' => 'Invalid File Format for Import',
					'msg' => 'The file format uploaded for import is invalid or could not be found. Only *.xls files are accepted',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000011' => array(
					'typ' => 'serror',
					'err' => 'Data not found',
					'msg' => 'There is no available data for generating progress status report',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000012' => array(
					'typ' => 'serror',
					'err' => 'Missing Help Topic',
					'msg' => 'The contents of the select help topic could not be found',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000013' => array(
					'typ' => 'serror',
					'err' => 'Invalid Help Topic',
					'msg' => 'The function selected do not have any help topic',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000014' => array(
					'typ' => 'serror',
					'err' => 'Unable to Authenticate Registered User',
					'msg' => 'Successful registration, but user could not be authenticated due to an unknown error',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000015' => array(
					'typ' => 'serror',
					'err' => 'Unable to Verify User Email Address',
					'msg' => 'Unable to verify the user email address',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000016' => array(
					'typ' => 'serror',
					'err' => 'Invalid School Settings',
					'msg' => 'Please ensure that school settings exists',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000017' => array(
					'typ' => 'serror',
					'err' => 'No Language File',
					'msg' => 'Class Name: '.$this->class_that_triggered_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000018' => array(
					'typ' => 'serror',
					'err' => 'Invalid Items to Checkout',
					'msg' => 'Class Name: '.$this->class_that_triggered_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000019' => array(
					'typ' => 'serror',
					'err' => 'Invalid Database Table',
					'msg' => 'Class Name: '.$this->class_that_triggered_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000020' => array(
					'typ' => 'serror',
					'err' => 'No Order ID was Specified',
					'msg' => 'Class Name: '.$this->class_that_triggered_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000021' => array(
					'typ' => 'serror',
					'err' => 'No Record Found',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000022' => array(
					'typ' => 'serror',
					'err' => 'Invalid Country',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000023' => array(
					'typ' => 'serror',
					'err' => 'Item was not added to Cart!',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000024' => array(
					'typ' => 'serror',
					'err' => 'Missing Information!',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000025' => array(
					'typ' => 'serror',
					'err' => 'System Error!',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000026' => array(
					'typ' => 'serror',
					'err' => 'Invalid Authentication Token',
					'msg' => 'Please ensure that the token you are trying to use has not expired',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				/*------------*/
				//User Errors
				/*------------*/
				'000101' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Input Data',
					'msg' => 'Please provide appropriate values in all required fields<br /><br />'.$this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'000102' => array(
					'typ' => 'uerror',
					'err' => 'Existing Email Address',
					'msg' => 'The email address your trying to register already exists.<br /><br />Consider resetting your password if its forgotten',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000103' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Password',
					'msg' => 'Please enter your current password in the <b>Current Password</b> Field.<br /><br />Consider resetting your password if its forgotten',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000104' => array(
					'typ' => 'uerror',
					'err' => 'Welcome '.$this->user_full_name,
					'msg' => 'To start enjoying our services, click on the verification link in the email that was sent to your email account [<i><b>'.$this->user_mail.'</b></i>] when you signed up.',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000105' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Session',
					'msg' => 'The system cannot proceed to the selected session because records already exists for the session',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000106' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Product',
					'msg' => 'The product selected is invalid and as a results variations cannot be managed',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000107' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Product Variation Quantity',
					'msg' => 'The quantity of product variations exceeds the total quantity instock, please increase your Total Stock-level to accommodate the changes',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000108' => array(
					'typ' => 'uerror',
					'err' => 'Listing Limit Exceeded',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				'000109' => array(
					'typ' => 'uerror',
					'err' => 'Already Subscribed to Merchant',
					'msg' => 'You have already been subscribed to the selected merchant',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-danger alert-danger',
				),
				/*------------*/
				//Successful Delete Operation
				/*------------*/
				'010001' => array(
					'typ' => 'deleted',
					'err' => 'Deleted',
					'msg' => 'The selected dataset has been deleted',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010001A' => array(
					'typ' => 'deleted',
					'err' => 'Restored',
					'msg' => 'The selected dataset has been restored',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010002' => array(
					'typ' => 'saved',
					'err' => 'Successful Update',
					'msg' => 'Your changes have been successfully saved',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010003' => array(
					'typ' => 'updated',
					'err' => 'Updated',
					'msg' => 'Data was successfully updated on disk',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010004' => array(
					'typ' => 'saved',
					'err' => 'Saved',
					'msg' => 'Data was successfully saved to disk but unable to update Sales Code',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'b',
				),
				'010005' => array(
					'typ' => 'saved',
					'err' => 'Successful Import',
					'msg' => 'Data was successfully imported and saved to disk',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010006' => array(
					'typ' => 'moved',
					'err' => 'Unable to move file',
					'msg' => 'System generated reports were not be moved to the public pool',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010007' => array(
					'typ' => 'moved',
					'err' => 'Successful Move',
					'msg' => 'Files were moved to the public pool',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010008' => array(
					'typ' => 'saved',
					'err' => 'Successful Update',
					'msg' => 'Your password has successfully been changed',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010009' => array(
					'typ' => 'report_generated',
					'err' => 'Successful Report Generation',
					'msg' => 'Data was successfully imported and saved to disk',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010010' => array(
					'typ' => 'report_generated',
					'err' => 'Generated Report was not logged',
					'msg' => 'Data was successfully imported and saved to disk',
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010011' => array(
					'typ' => 'saved',
					'err' => '',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010012' => array(
					'typ' => 'saved',
					'err' => 'Updating Selected Country',
					'msg' => 'Please wait...',
					'cls' => 'cCountry_list.php',
					'mth' => 'country-changed',
					'theme' => 'note note-success alert-success',
				),
				'010013' => array(
					'typ' => 'saved',
					'err' => 'Status Update',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-success alert-success',
				),
				'010014' => array(
					'typ' => 'uerror',
					'err' => '',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'alert-error note note-danger alert-danger',
				),
				'010015' => array(
					'typ' => 'saved',
					'err' => 'Token Successfully Generated',
                    'msg' => 'A Login Link has been sent to your email account, please click on the login link and sign-in once more',
					'cls' => 'cAuthentication.php',
					'mth' => 'notify',
					'theme' => 'note note-success alert-success',
				),
				'010016' => array(
					'typ' => 'saved',
					'err' => '',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-info alert-info',
				),
				'010018' => array(
					'typ' => 'saved',
					'err' => '',
					'msg' => $this->additional_details_of_error,
					'cls' => $this->class_that_triggered_error,
					'mth' => $this->method_in_class_that_triggered_error,
					'inf' => $this->additional_details_of_error,
					'theme' => 'note note-warning alert-info',
				),
				
				'010101' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Username',
					'msg' => 'Please provide a valid username and password',
					'cls' => 'cAuthentication.php',
					'mth' => 'authenticate',
					'theme' => 'note note-danger alert-danger',
				),
				'010102' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Password',
					'msg' => 'Please provide a valid username and password',
					'cls' => 'cAuthentication.php',
					'mth' => 'authenticate',
					'theme' => 'note note-danger alert-danger',
				),
				'010103' => array(
					'typ' => 'authenticated',
					'err' => 'Successful Sign In',
					'msg' => 'Successful Sign In',
					'cls' => 'cAuthentication.php',
					'mth' => 'authenticate',
					'theme' => 'note note-success alert-success',
				),
				'010104' => array(
					'typ' => 'authenticated',
					'err' => 'Unable to Set Session Variables',
					'msg' => 'Sessions variable could not be set after the user was successfully authenticated',
					'cls' => 'cAuthentication.php',
					'mth' => 'authenticate',
					'theme' => 'note note-danger alert-danger',
				),
				'010105' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Email Address',
					'msg' => 'Please provide a valid email address. The requested operation could not be executed.',
					'cls' => 'cAuthentication.php',
					'mth' => '_reset_user_password',
					'theme' => 'note note-danger alert-danger',
				),
				'010106' => array(
					'typ' => 'reset',
					'err' => 'Password Reset was Successful',
					'msg' => 'Your new password has been sent to your email address',
					'cls' => 'cAuthentication.php',
					'mth' => '_reset_user_password',
					'theme' => 'note note-success alert-success',
				),
				'020101' => array(
					'typ' => 'uerror',
					'err' => 'Empty Cart',
					'msg' => 'Your shopping cart is empty',
					'cls' => 'cCart.php',
					'mth' => 'deal_grid',
					'theme' => 'note note-danger alert-danger',
				),
				'020102' => array(
					'typ' => 'serror',
					'err' => 'Something went wrong',
					'msg' => 'Sorry, the data could not be saved. Please refresh the browser and try again.',
					'cls' => 'cFunctions.php',
					'mth' => 'save',
					'theme' => 'note note-danger alert-danger',
				),
				'020201' => array(
					'typ' => 'deleted',
					'err' => 'Deleted',
					'msg' => 'The selected dataset has been deleted',
					'cls' => 'cFunctions.php',
					'mth' => 'delete',
					'theme' => 'note note-success alert-success',
				),
				'020202' => array(
					'typ' => 'serror',
					'err' => 'Something went wrong',
					'msg' => 'Sorry, the data could not be deleted. Please refresh the browser and try again.',
					'cls' => 'cFunctions.php',
					'mth' => 'delete',
					'theme' => 'note note-danger alert-danger',
				),
				'030101' => array(
					'typ' => 'serror',
					'err' => 'Update Failed',
					'msg' => 'Sorry, the existing record could not be updated',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'save',
					'theme' => 'note note-danger alert-danger',
				),
				'030102' => array(
					'typ' => 'serror',
					'err' => 'Record Creation Failed',
					'msg' => 'Sorry, the system was unable to create a new record',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'save',
					'theme' => 'note note-danger alert-danger',
				),
				'030103' => array(
					'typ' => 'uerror',
					'err' => 'Invalid Input Data',
					'msg' => 'Sorry, the data submitted contained unacceptable characters',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'save',
					'theme' => 'note note-success alert-success',
				),
				'030104' => array(
					'typ' => 'saved',
					'err' => 'Successful Update',
					'msg' => 'Your changes have been successfully saved',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'save',
					'theme' => 'note note-success alert-success',
				),
				'030301' => array(
					'typ' => 'deleted',
					'err' => 'Deleted',
					'msg' => 'The selected dataset has been deleted',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'delete',
					'theme' => 'note note-success alert-success',
				),
				'030302' => array(
					'typ' => 'serror',
					'err' => 'Something went wrong',
					'msg' => 'Sorry, the data could not be deleted. Please refresh the browser and try again.',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'delete',
					'theme' => 'note note-danger alert-danger',
				),
				'030401' => array(
					'typ' => 'status_update',
					'err' => 'Status Update',
					'msg' => 'The selected dataset verification status has been updated',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'verify',
					'theme' => 'note note-success alert-success',
				),
				'030402' => array(
					'typ' => 'serror',
					'err' => 'Something went wrong',
					'msg' => 'Sorry, the dataset could not be verified. Please refresh the browser and try again.',
					'cls' => 'cglobal_g_m.php',
					'mth' => 'verify',
					'theme' => 'note note-danger alert-danger',
				),
				'040101' => array(
					'typ' => 'searched',
					'err' => 'Advance Search',
					'msg' => 'Successfully search executed query',
					'cls' => 'cSearch.php',
					'mth' => 'perform_search',
					'theme' => 'note note-success alert-success',
				),
				'040201' => array(
					'typ' => 'search_cleared',
					'err' => 'Cleared Search',
					'msg' => 'Search query was successfully cleared',
					'cls' => 'cSearch.php',
					'mth' => 'clear_search',
					'theme' => 'note note-success alert-success',
				),
				'040202' => array(
					'typ' => 'search_cleared',
					'err' => 'Cleared Search',
					'msg' => 'No search query was found',
					'cls' => 'cSearch.php',
					'mth' => 'clear_search',
					'theme' => 'note note-danger alert-danger',
				),
				'050101' => array(
					'typ' => 'column_toggle',
					'err' => 'Column Show / Hide',
					'msg' => 'Column Successfully Shown / Hidden',
					'cls' => 'cColumn_toggle.php',
					'mth' => 'column_toggle',
					'theme' => 'note note-success alert-success',
				),
				'050102' => array(
					'typ' => 'column_toggle',
					'err' => 'Column Show / Hide',
					'msg' => 'Sorry, something happened and the column could not be hidden / shown',
					'cls' => 'cColumn_toggle.php',
					'mth' => 'column_toggle',
					'theme' => 'note note-danger alert-danger',
				),
				'060104' => array(
					'typ' => 'saved',
					'err' => 'Loaded',
					'msg' => 'Audit Trail was successfully loaded from disk',
					'cls' => 'cAudit.php',
					'mth' => 'save',
					'theme' => 'note note-success alert-success',
				),
				/**Notifications**/
				'070001' => array(
					'typ' => 'status_update',
					'err' => 'Status Update',
					'msg' => 'All unread notifications have been marked as read',
					'cls' => 'cNotifications.php',
					'mth' => 'update_user_unread_notifications_to_read',
					'theme' => 'note note-success alert-success',
				),
				//Update Payment Status
				'100010' => array(
					'typ' => 'uerror',
					'err' => 'Pending Payment',
					'msg' => 'Sorry, the data submitted contained unacceptable characters',
					'cls' => 'cUpdate_payment_status.php',
					'mth' => $this->method_in_class_that_triggered_error,
					'theme' => 'note note-success alert-success',
				),
				'100015' => array(
					'typ' => 'uerror',
					'err' => 'Pending Payment',
					'msg' => 'Sorry, the data submitted contained unacceptable characters',
					'cls' => 'cUpdate_payment_status.php',
					'mth' => $this->method_in_class_that_triggered_error,
					'theme' => 'note note-success alert-success',
				),
				'100020' => array(
					'typ' => 'success',
					'err' => 'Your Order was Successfully Placed',
					'msg' => 'To ensure delivery of items ordered endeavour to complete payment within the next 24 hours',
					'cls' => 'cUpdate_payment_status.php',
					'mth' => $this->method_in_class_that_triggered_error,
					'theme' => 'note note-success alert-success',
				),
				'100030' => array(
					'typ' => 'success',
					'err' => 'Your Payment was Successful',
					'msg' => 'A copy of the receipt for this payment has been sent to your email [<i><b>',
					'cls' => 'cUpdate_payment_status.php',
					'mth' => $this->method_in_class_that_triggered_error,
					'theme' => 'note note-success alert-success',
				),
			);
		}
		
	}
?>