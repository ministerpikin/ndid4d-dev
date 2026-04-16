<?php
	/**
	 * My PDF Class
	 *
	 * @used in  				Generating PDF Reports
	 * @created  				14:51 | 27-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| My PDF
	|--------------------------------------------------------------------------
	|
	| Generates PDF reports in the Gas Helix
	|
	*/
	
	class cMypdf{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = '';
		
		//Directory for Entities
		private $entity_directory = 'files';
		
		//ID of Root Label
		private $current_label = '1300';	//Root Label
		
		//Report Orientation and paper size
		private $orientation = "portrait";
		private $paper = "a4";
		private $report_css_file = "pdf-report-grid";
		private $report_number_of_signatories = 0;
		
		private $stream = '';
		private $type_of_report = 'pdf';
		public $filename = '';
		public $html = '';
		
		function mypdf(){
			//INITIALIZE RETURN VALUE
			$returned_value = array();
			
			//CHECK FOR CURRENT MODULE
			if(isset($_GET['module']))$this->current_module = $_GET['module'];
			
			if((isset($_POST['orientation']) && $_POST['orientation']) ){
				$this->orientation = $_POST['orientation'];
			}
			
			if((isset($_POST['paper']) && $_POST['paper']) ){
				$this->paper = $_POST['paper'];
			}
			
			if((isset($_GET['orientation']) && $_GET['orientation'])){
				$this->orientation = $_GET['orientation'];
			}
			
			if(isset($_GET['paper']) && $_GET['paper']){
				$this->paper = $_GET['paper'];
			}
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create':
				$returned_value = $this->_create();
			break;
			case 'generate_html_front':
				$this->type_of_report = "html_front";
				$returned_value = $this->_generate_pdf();
			break;
			case 'generate_pdf':
				$this->type_of_report = "html";
				$returned_value = $this->_generate_pdf();
			break;
			case 'generate_html':
				$returned_value = $this->_generate_html();
			break;
			case 'generate_text':
				$this->type_of_report = "text";
				
				$returned_value = $this->_generate_pdf();
			break;
			case 'generate_word_document':
				$this->type_of_report = "msword";
				
				$returned_value = $this->_generate_pdf();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_html(){
			$returning_html_data = 'Generating HTML';
			$html_only = '';
			
			$authenticate_report = 0;
			$authenticated_report_id = 0;
			
			//CHECK IF HTML DATA HAS BEEN RECEIVED
			if(isset($_POST['html']) && $_POST['html']){
				
				//Set Records Id
				$new_record_id_base = get_new_id();
				
				//Set Current Timestamp
				$current_date_time = date("U");
				
				//DATE REPORT WAS GENERATED
				$date_of_generation = date("jS-F-Y-H-i");
				
				//Get Users Full Name
				$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
				if(isset($_SESSION[$current_user_details_session_key])){
					$current_user_session_details = $_SESSION[$current_user_details_session_key];
				}
				
				$creator = '';
				if(isset($current_user_session_details['fname']) && isset($current_user_session_details['lname'])){
					$creator = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
				}
				
				//Report Title
				$report_title = "Archived Dataset of ".$date_of_generation;
			
				if( isset( $_POST['report_title'] ) && $_POST['report_title']){
					$report_title = $_POST['report_title'];
				}
				
				$report_headers = '';
				
				//INSERT AUTHENTICATION ID FOR REPORT
				//if($authenticated_report_id){
					//$report_headers .= get_authenticated_report_header_file_contents($authenticated_report_id);
				//}
				
				//Set HTML HEADERS
				$temp_html = $this->_html_head($authenticated_report_id);
				$temp_temp = $this->_temp($report_title,$creator);
				
				$html_only = $temp_html.$temp_temp;
				
				if( file_exists($this->class_settings[ 'calling_page' ].$this->entity_directory.'/components/header/report.jpg') ){
					$report_headers .= '<table width="100%">';
					$report_headers .= '<thead>';
						$report_headers .= '<tr>';
							$report_headers .= '<th>';
								$report_headers .= '<img src="'.$this->class_settings[ 'calling_page' ].$this->entity_directory.'/components/header/report.jpg" alt="Header" />';
							$report_headers .= '</th>';
						$report_headers .= '</tr>';
					$report_headers .= '</thead>';
					$report_headers .= '</table>';
				}
				
				$this->html = $temp_html.$report_headers.$temp_temp;
				
				//Set html Data
				$html_only .= $_POST['html'] . '</body>';
				$this->html .= $_POST['html'] . '</body>';
				
				//Create Directory
				$directory_name = 'archives/'.date("Y");
				$directory = $this->class_settings[ 'calling_page' ].$directory_name;
				$dir = create_folder('',$directory,'');
				
				$directory_name .= '/'.date("F");
				$directory = $this->class_settings[ 'calling_page' ].$directory_name;
				$dir = create_folder('',$directory,'');
				
				write_file('',$dir.'/'.$new_record_id_base.'.php',"<?php \n\n //Authenticate User \n\n ".'$pagepointer'." = '../../../'; \n\n require_once ".'$pagepointer."includes/verify_user_privilege_to_view_archived_dataset.php"'."; \n\n echo str_replace('../entities','../../../entities',stripslashes('".addslashes($this->html)."')); \n\n ?>");
				
				if( file_exists( $dir.'/'.$new_record_id_base.'.php' ) ){
					$returning_html_data = '';
					
					$class_name = '';
					$description = 'Not Available';
					if(isset($_POST['current_module']) && $_POST['current_module']){
						$class_name = $_POST['current_module'];
						
						if( isset( $_SESSION[ $class_name ][ 'archived_dataset_description' ] ) && $_SESSION[ $class_name ][ 'archived_dataset_description' ] ){
							$description = $_SESSION[ $class_name ][ 'archived_dataset_description' ];
						}
					}
					
					$this->table = 'archived_dataset';
					
					//ARCHIVED DATA RECORD
					$function_settings = array(
						'database'=>$this->database_name,
						'connect'=>$this->database_connection,
						'table'=>$this->table,
						
						'ID' => $new_record_id_base,
						
						'ARCHIVED_DATASET1_DT10_DT1_DT1' => $directory_name.'/'.$new_record_id_base.'.php',
						'ARCHIVED_DATASET2_DT1_DT1' => $description,
						'ARCHIVED_DATASET3_DT1_DT1_DT5' => $class_name,
						
						'CREATOR9000_DT9_DT11' => $this->class_settings[ 'user_id' ],
						'CREATOR_ROLE_DT1_DT6' => $this->priv_id,
						'record_status' => '1',
						'CREATION_DATE_DT4_DT8' => $current_date_time,
						'modification_date' => $current_date_time,
						'IP_ADDRESS_DT1_DT10' => get_ip_address(),
						'modified_by' => $this->class_settings[ 'user_id' ],
					);
					insert_new_record_into_table($function_settings);
					
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError('000008');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_html';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
					
					/*
					//RECORD CREATION FAILED ERROR
					$err = new cError('000007');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_html';
					$err->additional_details_of_error = 'could not create record on line 253';
					
					return $err->error();
					*/
				}
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError('000009');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cMypdf.php';
				$err->method_in_class_that_triggered_error = '_generate_html';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _generate_pdf(){
			$returning_html_data = 'Generating PDF';
			$html_only = '';
			
			$project = get_project_data();
			
			$authenticate_report = 0;
			$authenticated_report_id = 0;
			
			$extension = "pdf";
			$download_button_caption = "Download PDF Report";
			$absolute_url = $this->class_settings[ 'calling_page' ];
			$print_url = isset( $_POST[ 'print_url' ] ) && $_POST[ 'print_url' ] ? $_POST[ 'print_url' ] : '';
			
			$script = "";
			switch( $this->type_of_report ){
			case "msword":
				$extension = "doc";
				$download_button_caption = "Download MS Word Report";
				//$this->report_css_file = "";
				
				if( isset( $project["domain_name"] ) ){
					$absolute_url = $project["domain_name"];
				}
			break;
			case "html_front":
				$extension = "html";
				$download_button_caption = "Print Report";
				//$this->report_css_file = "";
				
				if( isset( $project["domain_name"] ) ){
					$absolute_url = $project["domain_name"];
				}
				$script = '<script type="text/javascript">setTimeout(function(){ window.print(); } , 800 );</script>';
			break;
			case "html":
				$extension = "html";
				$download_button_caption = "Print Report";
				//$this->report_css_file = "";
				
				if( isset( $project["domain_name"] ) ){
					$absolute_url = $project["domain_name"];
				}
				
				$script = '<script type="text/javascript">setTimeout(function(){ window.print(); } , 800 );</script>';
			break;
			}
			
			//CHECK IF HTML DATA HAS BEEN RECEIVED
			if(isset($_POST['html']) && $_POST['html']){
				
				//Set Records Id
				$new_record_id_base = get_new_id();
				
				//Set Current Timestamp
				$current_date_time = date("U");
				
				//DATE REPORT WAS GENERATED
				$date_of_generation = date("jS F Y H:i");
				
				$body_content_headers = '';
				
				$reference = 'NAP/AXIS';
			
				//Get Users Full Name
				$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
				if(isset($_SESSION[$current_user_details_session_key])){
					$current_user_session_details = $_SESSION[$current_user_details_session_key];
				}
				
				$creator = '';
				$created_by = '';
				if(isset($current_user_session_details['fname']) && isset($current_user_session_details['lname'])){
					$creator = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
					
					$created_by = '<br />Created By: ' . $creator;
				}
				
				$creator_name = $creator;
				
				$report_headers = '';
				
				if( ! ( isset( $_POST[ 'report_show_user_info' ] ) && $_POST[ 'report_show_user_info' ] == 'yes' ) ){
					$_POST[ 'report_show_user_info' ] = $creator;
					$creator_name = '';
				}
				$rfrom = "";
				if( isset( $_POST[ 'rfrom' ] ) ){
					$rfrom = $_POST[ 'rfrom' ];
				}
				$rto = "";
				if( isset( $_POST[ 'rto' ] ) ){
					$rto = $_POST[ 'rto' ];
				}
				
				$rref = "";
				if( isset( $_POST[ 'rref' ] ) ){
					$rref = $_POST[ 'rref' ];
				}
				
				$archive_only = 0;
				if( isset( $_POST[ 'archive_only' ] ) && $_POST[ 'archive_only' ] ){
					$archive_only = $_POST[ 'archive_only' ];
				}
				
				$hidden_fields = isset( $_POST["hidden_fields"] )?$_POST["hidden_fields"]:'';
				$json = array();
				
				if( $hidden_fields ){
					$json = json_decode( $hidden_fields, true );
				}
				
				$report_reference = isset( $json["reference"] )?$json["reference"]:'';
				$report_reference_table = isset( $json["reference_table"] )?$json["reference_table"]:'';
				
				$logo_url = '';
				if( get_report_logo_file_settings() ){
					$logo_url = $absolute_url . get_report_logo_file_settings();
				}
				
				$direct_print = 0;
				if( isset( $_POST[ 'direct_print' ] ) ){
					$direct_print = $_POST[ 'direct_print' ];
				}
				
				$emails = '';
				$send_email = 0;
				if( isset( $_POST[ 'send_email' ] ) ){
					$send_email = $_POST[ 'send_email' ];
					$emails = trim( isset( $_POST[ 'emails' ] )?$_POST[ 'emails' ]:'' );
					$logo_url = '';
					$_POST[ 'report_title' ] = 'Email Sent to ' . $emails;
					$script = '';
					$_POST["exclude_header"] = 1;
				}
				
				if( $send_email && ! $emails ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Recipient Email</h4>Please specify the recipient email address';
					return $err->error();
				}
				
				$report_headers_file = '';
				$report_source_type = '';
				$report_description = '';
				$report_file_name = '';
				
				$include_reference_number = 1;
				
				$address = '';
				//$store = get_store_details( array( "id" => get_main_store() ) );
				$store = get_record_details( array( "id" => get_main_store(), "table" => "stores" ) );
				if( isset( $store["id"] ) ){
					$address = $store["address"];
					$address .= '<br />Tel: ' . $store["phone"];
					$address .= '<br />Email: ' . $store["email"];
				}
				
				if( ! ( isset( $_POST["exclude_header"] ) && $_POST["exclude_header"] ) ){
					$report_headers_file = get_report_content_header( array( 'extension' => $extension, 'logo_url' => $logo_url, 'to' => $rto, 'from' => $rfrom, 'ref' => strtoupper( $rref ), "address" => $address ) );
				}else{
					$include_reference_number = 0;
				}
				
				$share_report = 0;
				if( isset( $_POST["share_type"] ) && $_POST["share_type"] ){
					$share_report = $_POST["share_type"];
					$this->type_of_report = 'share';
					$script = '';
				}
				
				//Set HTML HEADERS
				$temp_html = $this->_html_head( $new_record_id_base , $creator_name, $include_reference_number );
				
				$html_only = $temp_html;
				
				//Create User Folder
				$dir = create_report_directory( $this->class_settings );
			
				//Set Output File Name
				$this->filename = $dir . '/s' . $new_record_id_base . '.' . $extension;
				
				$this->html = $temp_html;
				
				//Set html Data
				$html_only .= $report_headers_file . $_POST['html'] . $script . '</body>';
				$this->html .= $report_headers_file . $_POST['html'] . $script . '</body>';
				
				$report_title = 'New Report';
				if( isset( $_POST[ 'report_title' ] ) && $_POST[ 'report_title' ] ){
					$report_title = strip_tags( $_POST[ 'report_title' ] );
				}
				
				if( $send_email && $emails ){
					
					$failed = 0;
					$success_msg = '';
					$response = '';
					$message_type = 99;
					
					$d1["title"] = isset( $_POST["email_subject"] )?$_POST["email_subject"]:'REPORT AS AT ' . date("d-M-Y H:i");
					$d1["info"] = $html_only;
					
					$this->class_settings[ 'data' ] = $d1;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/email-message.php' );
					$this->class_settings['message'] = $this->_get_html_view();
					
					$this->class_settings['subject'] = strtoupper( $project["company_name"] ) . ': ' . $d1["title"];
					
					//$this->class_settings['attachment'] = array();
					$this->class_settings['message_type'] = $message_type;
					$this->class_settings['direct_message'] = 1;
					
					//$this->class_settings[ 'destination_full_name' ] = isset( $d["name"] )?$d["name"]:$d["email"];
					//$this->class_settings[ 'destination_id' ] = $d["email"];
					//$this->class_settings[ 'destination_email' ] = $d["email"];
					$this->class_settings[ 'destination_bcc_emails' ] = $emails;
					
					$status = $this->_send_email();
					$response .= ( isset( $status["response"] )?$status["response"]:'' );
					
					if( isset( $status["status"] ) && $status["status"] ){
						$err = new cError('010011');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = '<h4>Email Sent</h4>' . $emails;
						return $err->error();
					}else{
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = '<h4>Unable to Send Email</h4>' . $response;
						return $err->error();
					}
				}
				
				
				if( $share_report ){
					$action = "create_shared_report_prompt";
					
					switch( $share_report ){
					case 2:
					case "2":
						$action = "insert_into_report_prompt";
						$html_only = $_POST['html'];
					break;
					case "share":
						$action .= "2";
						$html_only = $_POST['html'];
					break;
					case "save":
						$action = "create_saved_report_prompt";
						$html_only = $_POST['html'];
					break;
					}
					
					// print_r( $action );exit;
					if( class_exists("cFiles") ){
						$f = new cFiles();
						$f->class_settings = $this->class_settings;
						$f->class_settings["report_data"] = $json;
						$f->class_settings["report_title"] = $report_title;
						$f->class_settings["report_content"] = $html_only;
						$f->class_settings["action_to_perform"] = $action;
						return $f->files();
					}
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Missing Dependency</h4><p>Please contact support team</p>';
					return $err->error();
				}
				
				//WRITE EDITABLE HTML FILE
				$editable_html_file_url = $dir.'/e'.$new_record_id_base.'.php';
				
				file_put_contents( $editable_html_file_url, $html_only );	//Insert only body content here
				
				//Generate Report
				switch( $this->type_of_report ){
				case "html_front":
				case "html":
				case "msword":
					file_put_contents( $this->filename , $this->html );
				break;
				default:
					$this->_create();
				break;
				}
				
				//Check if report was created
				if( file_exists($this->filename) ){
					//ADD REPORT TO DATABASE
					$reports_handler = new cReports();
					$reports_handler->class_settings = $this->class_settings;
					$reports_handler->class_settings[ 'action_to_perform' ] = 'add_generated_report_record';
					
					$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/s'.$new_record_id_base . '.' . $extension;
					
					$report_title = 'undefined';
					if( isset( $_POST[ 'report_title' ] ) && $_POST[ 'report_title' ] ){
						$report_title = strip_tags( $_POST[ 'report_title' ] );
					}
					
					$report_reference = 'undefined';
					if( isset( $_POST[ 'current_module' ] ) && $_POST[ 'current_module' ] ){
						$report_reference = $_POST[ 'current_module' ];	//table name
					}
					
					$reports_handler->class_settings[ 'file_properties' ] = array( 
						'file_name' => $report_title,
						'file_url' => $file_url,
						'file_reference' => $report_reference,
						'file_source' => $report_reference,
						'file_keywords' => $report_title,
						'file_description' => 'system generated report',
					);
					
					if( $reports_handler->reports() ){
						//successful notification
						$err = new cError('010009');
					}else{
						//record was not created
						$err = new cError('010010');
					}
					
					$file_rule = "";
					$file_url2 = $project["domain_name"] . $file_url;
					if( $this->type_of_report == "html_front" ){
						$file_rule = $file_url;
						$file_url = "../engine/" . $file_url;
						
						if( $print_url ){
							$file_url = $print_url."engine/".$file_rule;
						}
					}

					
					// print_r( $print_url );exit;
					
					$returning_html_data = 'File Created On: '.$date_of_generation . $created_by . '<br /><a class="btn btn-primary view-file-link-1" href="' . $file_url2 . '" target="_blank" ><i class="icon-download-alt icon-white">&nbsp;&nbsp;</i> '.$download_button_caption.'</a>';
					
					if( $archive_only ){
						$returning_html_data = '<h4>Archived Report</h4>Printed report archived on: '.$date_of_generation . $created_by . '<br />';
					}
					//$returning_html_data = 'File Created On: '.$date_of_generation . $created_by . '<br /><a class="btn btn-primary custom-action-button" month-id="' . $file_url . '" budget-id="-" href="#' . $file_url . '" function-id="7979957710" function-class="help" function-name="display_popup_menu" module-id="0" module-name="0" ><i class="icon-download-alt icon-white">&nbsp;&nbsp;</i> '.$download_button_caption.'</a>';
					
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = '';
	
					$return = $err->error();
					
					$return[ 'msg' ] = $returning_html_data;
					
					if( isset( $return[ 'html' ] ) )unset( $return[ 'html' ] );
					
					if( $file_rule ){
						$file_url = $file_rule;
					}
					
					if( $direct_print )
						$return["notification"] = $absolute_url . $file_url;
					
					//$return[ 'status' ] = "new-status";
					//$return[ 'javascript_functions' ] = array( "set_function_click_event" );
					
					return $return;
					
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError('000008');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
				}
				
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError('000009');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cMypdf.php';
				$err->method_in_class_that_triggered_error = '_generate_pdf';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'status' => 'generated-report',
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _create(){
			require_once( $this->class_settings[ 'calling_page' ] . "classes/dompdf/dompdf_config.inc.php"); 
			spl_autoload_register("DOMPDF_autoload");
			
			//Create new pdf file
			$dompdf = new DOMPDF();
			
			//$dompdf->load_file( $this->class_settings[ 'calling_page' ].'a.html' );
			//$html = $this->tidy( $this->html );
			//$this->filename = 'a.pdf';
			//$this->html = file_get_contents( 'a.html' );
			//$this->html = 'Boy o';
			
			$stylesheet = '';
			if( isset( $this->stylesheet ) && file_exists( $this->class_settings[ 'calling_page' ] . 'css/' . $this->stylesheet . '.css' ) ){
				$stylesheet = '<style>' . file_get_contents( $this->class_settings[ 'calling_page' ] . 'css/' . $this->stylesheet . '.css' ) . '</style>';
			}
			
			//file_put_contents("a.html", $this->html );
			$dompdf->load_html( $stylesheet . $this->html );
			
			$dompdf->set_paper( $this->paper, $this->orientation );
			/*
				<select name="orientation">
				  <option value="portrait">portrait</option>
				  <option value="landscape">landscape</option>
				</select>
			*/
			$dompdf->render();
			
			if ($this->stream==1) {
				$dompdf->stream( $this->filename );
			} elseif($this->stream==2) {
				return $dompdf->output();
			}else {
				$data = $dompdf->output();
				//Write data to file
				file_put_contents( $this->filename , $data );
			}
		}
	
		 /**
		 * Tidy the HTML for the PDF generation.
		 * @param type $html
		 * @param array $userConfig Configuration settings for Tidy. See the Tidy website for details.
		 * @return string The Tidy'd HTML
		 * @author The internets & jez
		 */  
		private function tidy($html, $userConfig = FALSE ) {       
			// default tidyConfig. Most of these are default settings.
			$config = array(
				'show-body-only' => false,
				'clean' => true,
				'char-encoding' => 'utf8',
				'add-xml-decl' => true,
				'add-xml-space' => true,
				'output-html' => false,
				'output-xml' => false,
				'output-xhtml' => true,
				'numeric-entities' => false,
				'ascii-chars' => false,
				'doctype' => 'strict',
				'bare' => true,
				'fix-uri' => true,
				'indent' => true,
				'indent-spaces' => 4,
				'tab-size' => 4,
				'wrap-attributes' => true,
				'wrap' => 0,
				'indent-attributes' => true,
				'join-classes' => false,
				'join-styles' => false,
				'enclose-block-text' => true,
				'fix-bad-comments' => true,
				'fix-backslash' => true,
				'replace-color' => false,
				'wrap-asp' => false,
				'wrap-jste' => false,
				'wrap-php' => false,
				'write-back' => true,
				'drop-proprietary-attributes' => false,
				'hide-comments' => false,
				'hide-endtags' => false,
				'literal-attributes' => false,
				'drop-empty-paras' => true,
				'enclose-text' => true,
				'quote-ampersand' => true,
				'quote-marks' => false,
				'quote-nbsp' => true,
				'vertical-space' => true,
				'wrap-script-literals' => false,
				'tidy-mark' => true,
				'merge-divs' => false,
				'repeated-attributes' => 'keep-last',
				'break-before-br' => true,
			);               
			
			if( is_array($userConfig) ) {
				$config = array_merge($config, $userConfig);           
			}

			$tidy = new tidy();
			$output = $tidy->repairString($html, $config, 'UTF8');        
			return($output);
		}
		
		private function _html_head($authenticated_report_id = '', $creator = '', $include_reference_number = 1 ){
			if(!$authenticated_report_id)
				$authenticated_report_id = '';
			
			$pr = get_project_data();
			
			$returning_html_data = '<head>';
			$returning_html_data .= '<style>';
				if( file_exists($this->class_settings[ 'calling_page' ].'css/'.$this->report_css_file.'.php' ) ){
					$this->class_settings[ 'html' ] = array( 'css/'.$this->report_css_file.'.php' );
					$returning_html_data .= $this->_get_html_view();
				}
			$returning_html_data .= '</style>';
			$returning_html_data .= '</head>';
			$returning_html_data .= '<body>';
			
			if( ! $include_reference_number )return $returning_html_data;
			
			//Check the type of report to be generated
			switch( $this->type_of_report ){
			case "text":
				//$returning_html_data .= '<input type="button" value="Print" onclick=" window.print(); " class="no-print" />';
				//$returning_html_data .= '<div id="report-authentication-id">&nbsp;&nbsp;&nbsp;'.$authenticated_report_id.'<span style="color:#aaa;"> - '.$creator.' | '.date('jS F Y H:i').'</span></div>';
			break;
			default:
				//$returning_html_data .= '<!--gashelixremovethis--><div id="watermark"><div id="report-authentication-id">&nbsp;&nbsp;&nbsp;'.strtoupper( $pr['app_title'] ).' - '.$authenticated_report_id.'<span style="color:#aaa;"> - '.$creator.' | '.date('jS F Y H:i').'</span></div></div><br /><!--gashelixremovethis-->';
			break;
			}
			
			return $returning_html_data;
		}
		
		private function _append_signatories_to_reports( $settings = array() ){
			$returning_html_data = '';
			
			$array_of_fields_for_signatory = array(
				'Organization',
				'Name',
				'Signature',
				'Date',
			);
			
			if( isset( $settings[ 'number_of_signatories' ] ) && $settings[ 'number_of_signatories' ] ){
			
				$returning_html_data .= '<br /><br /><table width="100%">';
				$returning_html_data .= '<tbody>';	
				$returning_html_data .= '<tr>';
				$returning_html_data .= '<td>';
				
				$returning_html_data .= '<fieldset style="font-family:arial; font-size:8pt;">';
				$returning_html_data .= '<legend>Signatories</legend>';
				$returning_html_data .= '<table width="100%">';
					$returning_html_data .= '<tbody>';
						$returning_html_data .= '<tr>';
							
						$returning_html_data .= '<td vertical-align="top">';
							$returning_html_data .= '<table width="100%">';
								$returning_html_data .= '<tbody>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '</tbody>';
							$returning_html_data .= '</table>';
						$returning_html_data .= '</td>';
						
						for( $signatories_count = 0; $signatories_count < $settings[ 'number_of_signatories' ]; $signatories_count++ ){
							$returning_html_data .= '<td vertical-align="top">';
								
								$returning_html_data .= '<table width="100%">';
								$returning_html_data .= '<tbody>';
								
								foreach( $array_of_fields_for_signatory as $signatory_field ){
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td width="10" ><b>' . $signatory_field . '</b></td>';
									$returning_html_data .= '<td style="border-bottom:1px solid #111;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
								$returning_html_data .= '</tr>';
								}
								
								$returning_html_data .= '</tbody>';
								$returning_html_data .= '</table>';
								
							$returning_html_data .= '</td>';
						}
							
						$returning_html_data .= '</tr>';
					$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				$returning_html_data .= '</fieldset>';
				
				$returning_html_data .= '</td>';
				$returning_html_data .= '</tr>';
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
			}
			
			return $returning_html_data;
		}
		
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		
		protected function _send_email(){
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ] = array();
			
			if( isset( $this->class_settings[ 'destination_bcc_emails' ] ) && $this->class_settings[ 'destination_bcc_emails' ] ){
				$email->class_settings[ 'destination' ]['bcc_emails'] = $this->class_settings[ 'destination_bcc_emails' ];
			}
			
			$email->class_settings[ 'destination' ]['email'][] = isset( $this->class_settings[ 'destination_email' ] )?$this->class_settings[ 'destination_email' ]:$this->class_settings[ 'user_email' ];
			$email->class_settings[ 'destination' ]['full_name'][] = isset( $this->class_settings[ 'destination_full_name' ] )?$this->class_settings[ 'destination_full_name' ]:$this->class_settings[ 'user_full_name' ];
			$email->class_settings[ 'destination' ]['id'][] = isset( $this->class_settings[ 'destination_id' ] )?$this->class_settings[ 'destination_id' ]:$this->class_settings[ 'user_id' ];
			
			return $email->emails();
		}
		
	}
?>