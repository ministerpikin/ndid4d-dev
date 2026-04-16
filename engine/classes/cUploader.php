<?php
	/**
	 * Uploader Class
	 *
	 * @used in  				php/upload.php
	 * @created  				-
	 * @database table name   	-
	 */

	/**
	 * Handle file uploads via XMLHttpRequest
	 */
	
			
	class qqUploadedFileXhr {
		/**
		 * Save the file to the specified path
		 * @return boolean TRUE on success
		 */
		function save($path) {
			$input = fopen("php://input", "r");
			$temp = tmpfile();
			$result_of_sql_queryealSize = stream_copy_to_stream($input, $temp);
			fclose($input);
			
			if ($result_of_sql_queryealSize != $this->getSize()){            
				return false;
			}
			
			$target = fopen($path, "w");        
			fseek($temp, 0, SEEK_SET);
			stream_copy_to_stream($temp, $target);
			fclose($target);
			
			return true;
		}
		
		function saveIIS($path) {
			$input = fopen("php://input", "r");
			//$temp = tmpfile();

			//$result_of_sql_queryealSize = stream_copy_to_stream($input, $temp);

			$target = fopen($path, "w");
			//fseek($temp, 0, SEEK_SET);
			$result_of_sql_queryealSize = stream_copy_to_stream($input, $target);
			fclose($input);
			fclose($target);

			if ($result_of_sql_queryealSize != $this->getSize()){
			unlink( $target );
			return false;
			}

			return true;
		}

		function getName() {
			return $_GET['qqfile'];
		}
		function getSize() {
			if (isset($_SERVER["CONTENT_LENGTH"])){
				return (int)$_SERVER["CONTENT_LENGTH"];            
			} else {
				throw new Exception('Getting content length is not supported.');
			}      
		}   
	}

	/**
	 * Handle file uploads via regular form post (uses the $_FILES array)
	 */
	class cUploaderdFileForm {  
		/**
		 * Save the file to the specified path
		 * @return boolean TRUE on success
		 */
		function save($path) {
			if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
				return false;
			}
			return true;
		}
		function getName() {
			return $_FILES['qqfile']['name'];
		}
		function getSize() {
			return $_FILES['qqfile']['size'];
		}
	}

	class cUploader {
		private $allowedExtensions = array();
		//private $sizeLimit = 10485760;
		private $sizeLimit = 3940;
		//private $sizeLimit = 2048;
		private $file;
		
		public $websalter = '107470839hxecddcNSCIVuafgw7fe78';
		public $database_connection = '';
		public $database_name = '';
		public $action_to_perform = '';
		public $calling_page = '../';
		
		public $user_id = '';
		public $priv_id = '';
		
		public $field_label = '';
		public $original_file_name = '';
		public $form_id = '';
		public $table_id = '';
		
		function __construct(array $allowedExtensions = array(), $sizeLimit = 2048){        
			$allowedExtensions = array_map("strtolower", $allowedExtensions);
				
			$this->allowedExtensions = $allowedExtensions;        
			$this->sizeLimit = $sizeLimit;
			
			$this->checkServerSettings();       

			if (isset($_GET['qqfile'])) {
				$this->file = new qqUploadedFileXhr();
			} elseif (isset($_FILES['qqfile'])) {
				$this->file = new qqUploadedFileForm();
			} else {
				$this->file = false; 
			}
		}
		
		private function checkServerSettings(){        
			$postSize = $this->toBytes( ini_get('post_max_size') );
			$uploadSize = $this->toBytes( ini_get('upload_max_filesize') );

			if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
				$size = max(1, $this->sizeLimit / 1024 / 1024 ) . 'M';
				die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
			}
		}
		
		private function toBytes($str){
			$val = doubleval( trim($str) );
			$last = strtolower($str[strlen($str)-1]);
			switch($last) {
				case 'g': $val *= 1024;
				case 'm': $val *= 1024;
				case 'k': $val *= 1024;        
			}
			return $val;
		}

		private function toBytesCorrected($str) {
		    $val = doubleval(trim($str));
		    $last = strtolower($str[strlen($str) - 1]);
		    switch ($last) {
	        case 'g':
	            $val *= 1024 * 1024 * 1024; // Convert to bytes (gigabytes)
            break;
	        case 'm':
	            $val *= 1024 * 1024; // Convert to bytes (megabytes)
            break;
	        case 'k':
	            $val *= 1024; // Convert to bytes (kilobytes)
            break;
		    }
		    return $val;
		}

		/**
		 * Returns array('success'=>true) or array('error'=>'error message')
		 */
		function handleUpload($uploadDirectory, $formControlElementName = 'default', $allowedExtensions = array(), $result_of_sql_queryeplaceOldFile = FALSE){
			
			if (!is_writable($uploadDirectory)){
				return array('error' => "Server error. Upload directory isn't writable.");
			}
			
			if (!$this->file){
				return array('error' => 'No files were uploaded.');
			}
			
			$size = $this->file->getSize();
			
			if ($size == 0) {
				return array('error' => 'File is empty');
			}
			
			if ($size > $this->sizeLimit) {
				return array('error' => 'File is too large');
			}
			
			$pathinfo = pathinfo($this->file->getName());
			
			//Change file name to id
			$filename = get_new_id();
			
			$filesize = $size;
			
			$filetitle = $pathinfo['filename'];
			
			//$filename = md5(uniqid());
			$ext = isset($pathinfo['extension']) && $pathinfo['extension'] ? $pathinfo['extension'] : '';
			
			$this->allowedExtensions = $allowedExtensions;
			
			if(!in_array(strtolower($ext), $this->allowedExtensions)){
				$these = implode(', ', $this->allowedExtensions);
				return array('error' => 'File has an invalid extension, it should be one of '. $these);
			}
			
			if(!$result_of_sql_queryeplaceOldFile){
				/// don't overwrite previous files that were uploaded
				while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
					$filename .= rand(10, 99);
				}
			}
			
			if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
				//One Last Check
				if(file_exists($uploadDirectory . $filename . '.' . $ext)){
					//Get form token
					
					//Store Temp Details of Uploaded File
					/*
					$up = md5( 'uploadedfile' . $this->form_id );
					$_SESSION[$up][$formControlElementName][] = array(
						'dir' => str_replace('../','',$uploadDirectory),
						'filename' => $filename,
						'ext' => $ext,
						'table' => $this->table_id,
					);
					*/
					$ocr = '';
					if( class_exists("cOcr_pdf2text") ){
						$ox = array();
						$ocrd = new cOcr_pdf2text();
						$ocr = $ocrd->ocr_pdf2text( $uploadDirectory, $filename, $ext, $ox );
					}
					
					$pr = get_project_data();
					
					if( defined("NWP_APP_DIR") && NWP_APP_DIR ){
						$uploadDirectory = str_replace( '/'.NWP_APP_DIR, '', $uploadDirectory );
					}
					
					$storeName = str_replace('../','',$uploadDirectory) . $filename .".". $ext;
					
					//19-jun-23: 2echo
					$did = md5( $filename );
					$hash = get_file_hash( array( "hash" => 1, "file_id" => $did, "date_filter" => 'd-M-Y' ) );
					$cn_path = $pr["domain_name"].'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $storeName, "key" => $hash ) ) .'&name='. rawurlencode( $filetitle );
					
					$f = array( 
						'success' => 'true',
						'title' => $filetitle,
						'label' => $this->field_label,
						'oname' => $this->original_file_name,
						'stored_name' => $storeName,
						'dir' => str_replace('../','engine/',$uploadDirectory),
						'fullname' => $pr["domain_name"] . str_replace('../','',$uploadDirectory),
						'filename' => $filename,
						'fpath' => $cn_path,
						'ext' => $ext,
						'element' => $formControlElementName,
						'ocr' => $ocr,
						'query_type' => 'FILE'
					);

					$gum = array();
					if( function_exists("get_gum") ){
						$gum = get_gum();
					}
					
					if( function_exists("create_update_manifest") ){
						//22-mar-23
						//print_r($gum); exit;
						$gum["manifest_suffix"] = "_fm_";
						$gum["data"] = $f;
						create_update_manifest("", "", "", $gum["manifest_suffix"], $gum );
					}
					
					return $f;
				}else{
					return array('error'=> 'Could not save uploaded file.' .
					'The upload was cancelled, or server error encountered');
				}
			} else {
				return array('error'=> 'Could not save uploaded file.' .
					'The upload was cancelled, or server error encountered');
			}
			
		}    
	}
	
?>