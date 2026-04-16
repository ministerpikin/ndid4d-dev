<?php
	$path = '/var/www/html/fermaedms.com.ng/public_html/edms/app/eres/backlog';
	__bulk_ocr_pdf_dir( $path, array() );
	
	function __bulk_ocr_pdf_dir( $path, $o = array() ){
		foreach( glob( $path . "/*" ) as $filename ) {
			if ( is_dir( $filename ) ) {
				echo "\n\ndir: ". $filename;
				__bulk_ocr_pdf_dir( $filename, $o );
				echo  "\n\n";
			}else if ( is_file( $filename ) ) {
				echo "file: ". $filename;
				$pf = pathinfo( $filename );
				if( isset( $pf["dirname"] ) && isset( $pf["filename"] ) ){
					$argv = [];
					$argv[3] = $filename;	//pdf file name
					$argv[4] = $pf["dirname"] ."/". $pf["filename"] . ".tiff";	//tiff file name
					$argv[5] = $pf["dirname"] ."/". $pf["filename"];	//file name without extension
					
					__bulk_ocr_pdf( $argv );
					if( file_exists( $argv[5] ) ){
						echo " ::success=1";
					}else{
						echo " ::fail=1";
					}
				}
				echo "\n";
			}
		}
	}
	
	function __bulk_ocr_pdf( $argv = [] ){
		$output=null;
		$retval=null;
		if( isset( $argv[4] ) && $argv[4] && isset( $argv[3] ) && $argv[3] ){
			
			if( file_exists( $argv[4] ) ){
				unlink( $argv[4] );
			}
			exec('convert -density 300 "'.$argv[3].'"  -depth 8 -strip -background white -alpha off "'.$argv[4].'"', $output, $retval);
			
			if( file_exists( $argv[4] ) ){
				//echo filesize( $argv[4] );
				
				if( isset( $argv[5] ) && $argv[5] ){
					if( file_exists( $argv[5] ) ){
						unlink( $argv[5] );
					}
					//cd "C:\Program Files\Tesseract-OCR\"
					//tesseract "%4" "%5" -l eng
					$output=null;
					$retval=null;
					exec('tesseract "'.$argv[4].'" "'.$argv[5].'" -l eng', $output, $retval);
				}
				unlink( $argv[4] );
			}
		}
	}
?>