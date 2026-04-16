<?php
	/**
	 * Emails Sending Class
	 *
	 * @used in  				/classes/*.php
	 * @created  				19:11 | 27-12-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Sends emails to users on execution of various actions
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	//include "AfricasTalkingGateway.php";
	
	class cEmails{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'emails';
		
		public $mail_certificate = array();
		public $mail_template = '';
		
		public $sender = array();
		
		private $table = 'emails';
		
		public $saved = 0;
		private $send_mail_from_remote_server = 0;
		
		public $message_type = 0;
		
		function emails(){
			//INITIALIZE RETURN VALUE
			$return = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'send_mail_queue':
				$return = $this->_send_mail_queue();
			break;
			case 'send_mail':
				$return = $this->_send_mail();
			break;
			case 'emails_log':
				$return = $this->_emails_log();
			break;
			case 'view_today_transaction_status':
			case 'display_all_records_full_view':
				$return = $this->_display_all_records_full_view();
			break;
			case "test_sms":
				$recipients = "+2348052529580,+23480334724399";
				$this->class_settings['destination']["phone"][] = $recipients;
				$this->class_settings['message'] = 'I Love A.G HON';
				$this->class_settings['message_type'] = 51;
				
				$return = $this->_send_mail();
			break;
			}
			
			return $return;
		}
		
		private function _send_mail_queue(){
			
			//execute periodic functions
			echo "Send email queue\n\n";
			if( function_exists("get_background_methods") ){
				$p = get_background_methods();
				if( is_array( $p ) && ! empty( $p ) ){
					foreach( $p as $class => $action_to_perform ){
						
						$actual_name_of_class = 'c'.ucwords($class);
						
						if( class_exists( $actual_name_of_class ) ){
							$module = new $actual_name_of_class();
							$module->class_settings = $this->class_settings;
							$module->class_settings["action_to_perform"] = $action_to_perform;
							$module->$class();
						}
						
					}
				}
			}
			
			$this->class_settings['message_type'] = 100;
			return $this->_send_mail();
		}
		
		private function _send_mail(){
			$h = '';
			$hbc = '';
			//foreach($this->class_settings['destination']['email'] as $k => $to){
				
				$fname = isset( $this->class_settings['destination']['full_name'] )?$this->class_settings['destination']['full_name']:array();
				
				$femail = isset( $this->class_settings['destination']['email'] )?$this->class_settings['destination']['email']:array();
					
				$message = isset( $this->class_settings['message'] )?$this->class_settings['message']:"";
				$subject = isset( $this->class_settings['subject'] )?$this->class_settings['subject']:"";
				$sender = isset( $this->class_settings[ 'sender' ] )?$this->class_settings[ 'sender' ]:'';
				$bcc_emails = isset( $this->class_settings["destination"]['bcc_emails'] )?$this->class_settings["destination"]['bcc_emails']:'';
				
				$cc_emails = isset( $this->class_settings["destination"]['cc_emails'] )?$this->class_settings["destination"]['cc_emails']:'';
				
                /*
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Additional headers
				$headers .= 'To: '.$this->class_settings['destination']['full_name'][$k].' <'.$to.'>' . "\r\n";
				$headers .= 'From: '.$this->class_settings['project_data']['project_name'].' <'.$this->class_settings['project_data']['email'].'>' . "\r\n";
				$headers .= 'Bcc: '.$this->class_settings['project_data']['admin_email'] . "\r\n";
				*/
				//Exempt Particular Emails
				$attachments = array();
				
				
				if( isset( $this->class_settings['attachment'] ) )
					$attachments = $this->class_settings['attachment'];
				/*
				$this->class_settings['topdf'][] = '<h1>Help Me 1</h1><h2>Help Me 2</h2><h3>Help Me 3</h3>';
				$subject = 'Hello ' . rand(1,1000);
				$message = 'James is here please view attached document';
				$this->class_settings['destination']['email'][] = 'pat2echo@gmail.com';
				$this->class_settings['destination']['full_name'][] = 'Patrick';
				$this->class_settings['message_type'] = 100;
				*/
				switch( $this->class_settings['message_type'] ){
				case 101:
					//check if local or online version
					$cache_data = array(
						'pagepointer' => $this->class_settings[ 'calling_page' ],
						'subject' => $subject,
						'message' => $message,
						'direct_message' => isset( $this->class_settings['direct_message'] )?$this->class_settings['direct_message']:0,
						'topdf' => isset( $this->class_settings['topdf'] )?$this->class_settings['topdf']:array(),
						'attachments' => $attachments,
						'recipient_fullnames' => $fname,
						'recipient_emails' => $femail,
						'bcc_emails' => $bcc_emails,
						'cc_emails' => $cc_emails,
					);
					/*
					if( isset( $cache_data['topdf'] ) && is_array( $cache_data['topdf'] ) && !empty( $cache_data['topdf'] ) ){
						$my_pdf = new cMypdf();
						$my_pdf->class_settings = $this->class_settings;
						$my_pdf->class_settings[ 'action_to_perform' ] = 'create';
						
						$file_number = 1;
						
						foreach( $cache_data['topdf'] as $topdf ){
							$file_name = $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/".date("U")."-".rand(0,500)."-".$file_number.".pdf";
							$loop = 1;
							while( $loop ){
								if( ! file_exists( $file_name ) ){
									$loop = 0;
									break;
								}else{
									++$file_number;
									$file_name = $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/".date("U")."-".rand(600,5000)."-".$file_number.".pdf";
								}
							}
							
							$my_pdf->stylesheet = 'pdf-sheet';
							$my_pdf->html = $topdf;
							$my_pdf->filename = $file_name;
							$my_pdf->mypdf();
							
							if( file_exists( $my_pdf->filename ) )
								$cache_data['attachments'][] = $my_pdf->filename;
						}
						unset( $cache_data['topdf'] );
					}
					*/
					$return = queue_email_notification( array( "data" => $cache_data ) );
					
					//file_put_contents( 'b.html' , $this->class_settings['topdf'][0] );
					//try sending mail
					//run_in_background( "send_email_queue" ); //have been moved to bg-processor
					
					
					return $return;
					//online: send notification
				break;
				case 100:
					$message = '';
					//check if internet access exists
					// if( check_for_internet_connection() ){
					if( 1 ){
						/*
						//zip attachments
						create_zip($files = array(), $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/" , true );
						
						//zip messages
						create_zip($files = array(), $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/" , true );
						
						//upload zip files online
						
						//confirm upload
						*/
						//check_queue_email_notification() //send push notification
						$loop = 1;
						$report_data = array();
						$report = array();
						$fail = 0;
						$key = '';
						if( isset( $this->class_settings["key"] ) && $this->class_settings["key"] ){
							$key = $this->class_settings["key"];
						}
						
						$queue = get_queue_email_notification( array( "key" => $key ) );
						
						if( isset( $queue["key"] ) && $queue["key"] && isset( $queue["data"] ) ){
							$my_pdf = new cMypdf();
							$my_pdf->class_settings = $this->class_settings;
							$my_pdf->class_settings[ 'action_to_perform' ] = 'create';
							
							$audit = new cAudit();
							$audit->class_settings = $this->class_settings;
							$audit->class_settings[ 'action_to_perform' ] = 'upload_mail_to_remote_server';
							
							analytics_update( array( "event" => 'email' ) );
							
							//print_r( $queue ); exit;
							while( $loop ){
								/*
								$cache_key = "email-manifest";
								$settings = array(
									'cache_key' => $cache_key."-".$queue["key"],
									'directory_name' => $cache_key,
									'permanent' => true,
								);
								$datas = get_cache_for_special_values( $settings );
								*/
								
								$datas = $queue["data"];
								if( is_array( $datas ) && ! empty( $datas ) ){
									foreach( $datas as $data ){
										
										//send mail
										$report = array();
										
										if( isset( $data['recipient_emails'] ) && !empty( $data['recipient_emails'] ) ){
											//prepare email notification
											$data['attachments'] = isset( $data['attachments'] )?$data['attachments']:array();
											
											if( isset( $data['topdf'] ) && is_array( $data['topdf'] ) && !empty( $data['topdf'] ) ){
												
												$file_number = 1;
												
												foreach( $data['topdf'] as $topdf ){
													$file_name = $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/".date("U")."-".rand(0,500)."-".$file_number.".pdf";
													$loop = 1;
													while( $loop ){
														if( ! file_exists( $file_name ) ){
															$loop = 0;
															break;
														}else{
															++$file_number;
															$file_name = $this->class_settings["calling_page"] . "tmp/sent_mails/attachment/".date("U")."-".rand(600,5000)."-".$file_number.".pdf";
														}
													}
													
													$my_pdf->stylesheet = 'pdf-sheet';
													$my_pdf->html = $topdf;
													$my_pdf->filename = $file_name;
													$my_pdf->mypdf();
													
													if( file_exists( $my_pdf->filename ) )
														$data['attachments'][] = $my_pdf->filename;
												}
												unset( $data['topdf'] );
											}
											
											$this->class_settings[ 'data' ] = array();
											$this->class_settings[ 'data' ]["full_name"] = isset( $data['recipient_fullnames'] )?implode( ',', $data['recipient_fullnames'] ):implode( ',', $data['recipient_emails'] );
											$this->class_settings[ 'data' ]["email"] = implode( ',', $data['recipient_emails'] );
											
											if( isset( $data['direct_message'] ) && $data['direct_message'] ){
												
											}else{
												$this->class_settings[ 'data' ]["title"] = $data["subject"];
												$this->class_settings[ 'data' ]["info"] = $data["message"];
												//$this->class_settings[ 'data' ]["skip_style"] = 1;
												$this->class_settings[ 'data' ]["mail_type"] = 1;
												
												$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/email-message.php' );
												$data['message'] = $this->_get_html_view();
											}
											
											if( $this->send_mail_from_remote_server ){
												$audit->class_settings[ 'data' ] = $data;
												$report = $audit->audit();
											}else{
												$report = send_mail( $data );
											}
											
											sleep(10);
											$report[ "date" ] = date("U");
											$report[ "recipient" ] = implode(',', $data['recipient_emails'] );
											$report[ "subject" ] = $data['subject'];
											
											if( ! ( isset( $report["status"] ) && $report["status"] ) ){
												++$fail;
											}
											//if failed, try sending to failback email address
											//if failed too, cancel operation & report
										}
										$report_data[] = $report;
										
									}
									clear_queue_email_notification( array( "key" => $queue["key"] ) );
									$message = 'Messages Sent: ' . count( $report_data ) . '. Failed Messages: ' . $fail;
									
									//check for next notification
									$loop = 0;
									$queue = array();
									$queue = get_queue_email_notification();
									if( isset( $queue["key"] ) && $queue["key"] ){
										//trigger a repeat process
										$loop = 1;
									}
								}else{
									$message = 'Empty content of queue';
								}
							}
						}else{
							$message = 'No Message in queue.';
							if( isset( $queue["key"] ) && $queue["key"] ){
								$message .= ' ' . $queue["key"];
								clear_queue_email_notification( array( "key" => $queue["key"] ) );
								unset( $queue["key"] );
							}
							
						}
						//yes: send notification to web server
						
						//*receive status update from internet server
						
					}else{
						//*no internet
						$message = 'no internet access';
					}
					//use push notifications for app to notify user
					return $message;
				break;
				case 95:
				case 96:
				case 98:
				case 99:
				break;
				case 2:		//Account Verification Email after registration
				case 15:	//Admin Login Token
				case 23:	//Send Pick-up Email to Delivery Agent
				case 40:	//Send Pick-up Email to Delivery Agent
				case 50:	//bulk message sending - email
					
				break;
				case 51:	//bulk message sending - sms: multiple users at once
					
					if( ! ( isset( $this->class_settings['destination']["phone"] ) && is_array( $this->class_settings['destination']["phone"] ) && ! empty( $this->class_settings['destination']["phone"] ) ) ){
						//invalid phone numbers
						return 0;
					}
					
					if( ! ( isset( $this->class_settings['message'] ) && $this->class_settings['message'] ) ){
						return 0;
					}
					
					$username   = get_project_africastalking_username(); //"catholagosphilip";
					$apikey     = get_project_africastalking_api(); //"4e043e9b2c91c6657ecc2fea55f7892a00f2a10674d58a156242547e79248a08";
					
					$from = get_project_africastalking_from();
					
					// And of course we want our recipients to know what we really do
					$message = '';
					if( isset( $this->class_settings['subject'] ) && $this->class_settings['subject'] ){
						$message = $this->class_settings['subject'] . " \n\n";
					}
					$message    .= strip_tags( $this->class_settings['message'] );
					
					// Create a new instance of our awesome gateway class
					$gateway    = new AfricasTalkingGateway( $username, $apikey );
					
					$r = array();
					
					//$recipients = "+2348052529580,+2348033472439";
					foreach( $this->class_settings['destination']["phone"] as $recipients ){
						try 
						{ 
						  // Thats it, hit send and we'll take care of the rest. 
						  if( $from ){
							  $results = $gateway->sendMessage( $recipients , $message, $from );
						  }else{
							 $results = $gateway->sendMessage( $recipients , $message ); 
						  }
						  
							/*
							Array ( [0] => stdClass Object ( [statusCode] => 101 [number] => +2348052529580 [cost] => NGN 2.2000 [status] => Success [messageId] => ATXid_cd3090ee8ea1bdfb02bc86b26a3b9e30 ) [1] => stdClass Object ( [statusCode] => 101 [number] => +2348033472439 [cost] => NGN 3.0000 [status] => Success [messageId] => ATXid_45e7671cb60065a59cbe48f4937d00a1 ) )
							*/
							
							$date = date( "d-M-Y H:i" );
							$stop_key = "sms".$date;
							
						  foreach($results as $result) {
							$rd = array( "status" => 0, "response" => $result->status, "cache_key" => $stop_key, "date" => $date );
							
							if( strtolower( $result->status ) == "success" ){
								$rd["status"] = 1;
							}
							
							$r[ $result->number ] = $rd;
							/*
							// status is either "Success" or "error message"
							echo " Number: " .$result->number;
							echo " Status: " .$result->status;
							echo " StatusCode: " .$result->statusCode;
							echo " MessageId: " .$result->messageId;
							echo " Cost: "   .$result->cost."\n";
							*/
						  }
						  $r[ "response" ] = "Message Sent";
						  $r[ "status" ] = 1;
						  
						}catch ( AfricasTalkingGatewayException $e ){
						  echo "Encountered an error while sending: ".$e->getMessage();
						}
					}
					
					return $r;
				break;
				default:
					if( class_exists("cNotifications") ){
						//Log Notification
						$this->class_settings['notification_data'] = array(
							'title' => $this->class_settings['subject'],
							'detailed_message' => strip_tags( $message , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><b><strong><i><u><small><i><u><super><sub>' ),
							'send_email' => 'no',
							'notification_type' => 'no_task',
							'target_user_id' => $this->class_settings['user_id'],
							'class_name' => $this->table_name,
							'method_name' => 'send_mail',
						);
						
						$notifications = new cNotifications();
						$notifications->class_settings = $this->class_settings;
						$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
						$notifications->notifications();
					}
				break;
				}
                
				return send_mail( array(
                    'pagepointer' => $this->class_settings[ 'calling_page' ],
                    'subject' => $subject,
                    'message' => $message,
                    'attachments' => $attachments,
                    'recipient_fullnames' => $fname,
                    'recipient_emails' => $femail,
                    'sender_fullname' => $sender,
					'bcc_emails' => $bcc_emails,
					'cc_emails' => $cc_emails,
                ) );
				/*
				if( ! empty( $attachments ) ){
					foreach( $attachments as $attach )unlink( $attach );
				}
				*/
			//}
		}
        
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notice.php' );
			$notice = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form.php' );
			$form = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			//$datatable = $this->_display_data_table();
			//$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['form_data'] = $form;
			$this->class_settings[ 'data' ]['html'] = $notice;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "Email Reports";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ) 
				//'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 
			);
		}
		
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		private function _emails_log(){
			$return = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_today_transaction_status':
				$_POST["date"] = date("Y-m-D");
			break;
			}
			
			$emails = array();
			$date = '';
			$title = '';
			if( isset( $_POST["date"] ) && $_POST["date"] ){
				$date = $_POST["date"];
				$title = "Showing Emails Sent On ".$date;
				
				//check for all mails within 24hour period
				$ds = explode( "-", $date );
				if( isset( $ds[0] ) && isset( $ds[2] ) ){
					$stop_key = $this->table_name . date( $ds[2]."-".$ds[1]."-".$ds[0]."-" );
					for( $i = 0; $i < 24; $i++ ){
						if( $i < 10 )$k = $stop_key."0".$i;
						else $k = $stop_key.$i;
						
						$cache = get_cache_for_special_values( array( 'cache_key' => $k, 'directory_name' => $this->table_name, 'permanent' => true ) );
						if( is_array( $cache ) && ! empty( $cache ) ){
							$emails[ $i.":00" ] = $cache;
						}
					}
				}
				
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/report.php' );
			$this->class_settings[ 'data' ] = array( 
				'emails' => $emails,
				'date' => $date,
				'title' => $title,
				'total' => 0,
			);
			
			$return['html_replacement'] = $this->_get_html_view();
			$return['html_replacement_selector'] = "#data-table-section";
            
            $return['status'] = 'new-status';
			
			return $return;
		}
		
	}
?>
<?php
/**
 * Simple Server Side Analytics
 *
 */

class ssga {
	const GA_URL = 'https://stats.g.doubleclick.net/__utm.gif'; //@nczz update v5.6.4dc

	private $data = array(
		'utmac' => null,
		'utmcc' => null,
		'utmcn' => null,
		'utmcr' => null,
		'utmcs' => null,
		'utmdt' => '-',
		'utmfl' => '-',
		'utme' => null,
		'utmni' => null,
		'utmhn' => null,
		'utmipc' => null,
		'utmipn' => null,
		'utmipr' => null,
		'utmiqt' => null,
		'utmiva' => null,
		'utmje' => 0,
		'utmn' => null,
		'utmp' => null,
		'utmr' => null,
		'utmsc' => '-',
		'utmvp' => '-',
		'utmsr' => '-',
		'utmt' => null,
		'utmtci' => null,
		'utmtco' => null,
		'utmtid' => null,
		'utmtrg' => null,
		'utmtsp' => null,
		'utmtst' => null,
		'utmtto' => null,
		'utmttx' => null,
		'utmul' => '-',
		'utmhid' => null,
		'utmht' => null,
		'utmwv' => '5.6.4dc' );

	private $tracking;


	public function __construct( $UA = null, $domain = null ) {
		$this->data['utmac'] = $UA;
		$this->data['utmhn'] = isset( $domain ) ? $domain : $_SERVER['SERVER_NAME'];
		$this->data['utmp'] = $_SERVER['PHP_SELF'];
		$this->data['utmn'] = rand( 1000000000, 9999999999 );
		$this->data['utmr'] = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
		$this->data['utmcc'] = $this->create_cookie();
		$this->data['utmhid'] = rand( 1000000000, 9999999999 );
		$this->data['utmht'] = time() * 1000;
	}

	/**
	 * Create the GA callback url, aka the gif
	 * 
	 * @return string
	 */
	public function create_gif() {
		$data = array();
		foreach ( $this->data as $key => $item ) {
			if ( $item !== null ) {
				$data[$key] = $item;
			}
		}
		return $this->tracking = self::GA_URL . '?' . http_build_query( $data );
	}

	/**
	 * Send tracking code/gif to GB
	 * 
	 * @return 
	 */
	public function send() {
		if ( !isset( $this->tracking ) )
			$this->create_gif();

		return $this->remote_call();
	}

	/**
	 * Use WP's HTTP class or CURL or fopen 
	 * @return array|null 
	 */
	private function remote_call() {

		if ( function_exists( 'wp_remote_head' ) ) { // Check if this is being used with WordPress, if so use it's excellent HTTP class

			$response = wp_remote_head( $this->tracking );
			return $response;

		} elseif ( function_exists( 'curl_init' ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $this->tracking );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false); //@nczz Fixed HTTPS GET method
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_exec( $ch );
			curl_close( $ch );
		} else {
			$handle = fopen( $this->tracking, "r" );
			fclose( $handle );
		}
		return;
	}

	/**
	 * Reset Defaults
	 * @return null
	 */
	public function reset() {
		$data = array(
			'utmac' => null,
			'utmcc' => $this->create_cookie(),
			'utmcn' => null,
			'utmcr' => null,
			'utmcs' => null,
			'utmdt' => '-',
			'utmfl' => '-',
			'utme' => null,
			'utmni' => null,
			'utmipc' => null,
			'utmipn' => null,
			'utmipr' => null,
			'utmiqt' => null,
			'utmiva' => null,
			'utmje' => '0',
			'utmn' => rand( 1000000000, 9999999999 ),
			'utmp' => $_SERVER['PHP_SELF'],
			'utmr' => isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '',
			'utmsc' => '-',
			'utmsr' => '-',
			'utmt' => null,
			'utme' => null,
			'utmtci' => null,
			'utmtco' => null,
			'utmtid' => null,
			'utmtrg' => null,
			'utmtsp' => null,
			'utmtst' => null,
			'utmtto' => null,
			'utmttx' => null,
			'utmul' => 'php',
			'utmht' => time() * 1000,
			'utmwv' => '5.6.4dc' );
		$this->tracking = null;
		return $this->data = $data;
	}

	/**
	 * Create unique cookie
	 * @return string 
	 */
	private function create_cookie() {
		$rand_id = rand( 10000000, 99999999 );
		$random = rand( 1000000000, 2147483647 );
		$var = '-';
		$time = time();
		$cookie = '';
		$cookie .= '__utma=' . $rand_id . '.' . $random . '.' . $time . '.' . $time . '.' . $time . '.2;+';
		$cookie .= '__utmb=' . $rand_id . ';+';
		$cookie .= '__utmc=' . $rand_id . ';+';
		$cookie .= '__utmz=' . $rand_id . '.' . $time . '.2.2.utmccn=(direct)|utmcsr=(direct)|utmcmd=(none);+';
		$cookie .= '__utmv=' . $rand_id . '.' . $var . ';';
		return $cookie;
	}

	////////////
	// Params //
	////////////


	/////////////
	// Product //
	/////////////

	public function set_product_code( $var = null ) {
		return $this->data['utmipc'] = $var;
	}

	public function set_product_name( $var = null ) {
		return $this->data['utmipn'] = $var;
	}

	public function set_unit_price( $var = null ) {
		return $this->data['utmipr'] = $var;
	}

	public function set_qty( $var = null ) {
		return $this->data['utmiqt'] = $var;
	}

	public function set_variation( $var = null ) {
		return $this->data['utmiva'] = $var;
	}

	//////////
	// Misc //
	//////////


	public function set_java( $var = null ) {
		return $this->data['utmje'] = $var;
	}


	public function set_encode_type( $var = null ) {
		return $this->data['utmcs'] = $var;
	}

	public function set_flash_version( $var = null ) {
		return $this->data['utmfl'] = $var;
	}


	public function set_host( $var = null ) {
		return $this->data['utmhn'] = $var;
	}

	public function set_screen_depth( $var = null ) {
		return $this->data['utmsc'] = $var;
	}


	public function set_screen_resolution( $var = null ) {
		return $this->data['utmsr'] = $var;
	}

	public function set_lang( $var = null ) {
		return $this->data['utmul'] = $var;
	}

	public function set_ga_version( $var = null ) {
		return $this->data['utmwv'] = isset( $var ) ? $var : $this->data['utmwv'];
	}

 	//////////
	// Page //
 	//////////

	public function set_page( $var = null ) {
		return $this->data['utmp'] = $var;
	}


	public function set_page_title( $var = null ) {
		return $this->data['utmdt'] = $var;
	}


	public function set_campaign( $var=null ) {
		return $this->data['utmcn'] = $var;
	}


	public function clone_campaign( $var=null ) {
		return $this->data['utmcr'] = $var;
	}

	public function set_referal( $var = null ) {
		return $this->data['utmr'] = $var;
	}

	////////////
	// Events //
	////////////

	public function set_event( $category, $action, $label = '', $value = '', $opt_noninteraction = false) {
		$event_category = (string) $category;
		$event_action = (string) $action;

		$event_string = '5(' . $event_category . '*' . $event_action;

		if (!empty($label)) {
			$event_string .= '*' . ((string) $label) . ')';
		} else {
			$event_string .= ')';
		}

		if (!empty($value)) {
			$event_string .= '(' . ((int) intval($value)) . ')';
		}

		if ($opt_noninteraction) {
			$this->data['utmni'] = '1';
		}

		$this->data['utmt'] = 'event';
		return $this->data['utme'] = $event_string;
	}

	///////////
	// Order //
	///////////

	public function set_order_id( $var = null ) {
		return $this->data['utmtid'] = $var;
	}

	public function set_billing_city( $var = null ) {
		return $this->data['utmtci'] = $var;
	}

	public function set_billing_country( $var = null ) {
		return $this->data['utmtco'] = $var;
	}

	public function set_billing_region( $var = null ) {
		return $this->data['utmtrg'] = $var;
	}


	public function set_shipping_cost( $var = null ) {
		return $this->data['utmtsp'] = $var;
	}


	public function set_affiliate( $var = null ) {
		return $this->data['utmtst'] = $var;
	}


	public function set_total( $var = null ) {
		return $this->data['utmtto'] = $var;
	}

	public function set_taxes( $var = null ) {
		return $this->data['utmttx'] = $var;
	}
	
	////////////////////////
	// Ecommerce Tracking //
	////////////////////////
	
	private static $requests_for_this_session = 0;
	
	/**
	 * Create and send a transaction object
	 * 
	 * Parameter order from https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingEcommerce
	 */
	public function send_transaction($transaction_id, $affiliation, $total, $tax, $shipping, $city, $region, $country) {
		$this->data['utmvw'] = '5.6.4dc';
		$this->data['utms'] = ++self::$requests_for_this_session;
		$this->data['utmt'] = 'tran';
		$this->data['utmtid'] = $transaction_id;
		$this->data['utmtst'] = $affiliation;
		$this->data['utmtto'] = $total;
		$this->data['utmttx'] = $tax;
		$this->data['utmtsp'] = $shipping;
		$this->data['utmtci'] = $city;
		$this->data['utmtrg'] = $region;
		$this->data['utmtco'] = $country;
		$this->data['utmcs'] = 'UTF-8';
		
		$this->send();
		$this->reset();
		
		return $this;
	}
	
	/**
	 * Add item to the created $transaction_id
	 * 
	 * Parameter order from https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingEcommerce
	 */
	public function send_item($transaction_id, $sku, $product_name, $variation, $unit_price, $quantity) {
		$this->data['utmvw'] = '5.6.4dc';
		$this->data['utms'] = ++self::$requests_for_this_session;
		$this->data['utmt'] = 'item';
		$this->data['utmtid'] = $transaction_id;
		$this->data['utmipc'] = $sku;
		$this->data['utmipn'] = $product_name;
		$this->data['utmiva'] = $variation;
		$this->data['utmipr'] = $unit_price;
		$this->data['utmiqt'] = $quantity;
		$this->data['utmcs'] = 'UTF-8';
		
		$this->send();
		$this->reset();
		
		return $this;
	}
}
?>