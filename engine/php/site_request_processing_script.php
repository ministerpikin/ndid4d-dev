<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Farmtech Process AJAX Request File
	|--------------------------------------------------------------------------
	|
	| Receives and processes all AJAX Server requests from the clients
	|
	*/
	
	$pagepointer = '../';
	
	$return_website_object = true;
	
	require_once "ajax_request_processing_script.php";
	
    //if( ! isset( $website_object ) ){
        if( isset( $_GET ) && $_GET ){
            $getparams = '';
            
            foreach( $_GET as $k => $v ){
                if($getparams)$getparams .= '&'.$k.'='.$v;
                else $getparams = $k.'='.$v;
            }
            
            header('Location: ../../?'.$getparams);
        }
    //}
	$app = json_decode( $website_object );
	
    
?>	
	<!DOCTYPE>
	<html>
	<head>
	<style id="pagepointer"><?php echo $pagepointer; ?></style>
<?php	
	if( isset( $app->stylesheet ) )
		echo $app->stylesheet;
		
	if( isset( $app->html_head_tag ) )
		echo $app->html_head_tag;
?>	
	</head>
	<body class="<?php if( isset( $app->action_performed ) ) echo $app->action_performed; ?>"><div class="page-wrapper" id="wrapper">
<?php	
	if( isset( $app->html_markup ) )
		echo $app->html_markup;
	
	if( isset( $app->html_header ) )
		echo $app->html_header;
	
	if( isset( $app->html ) )	
		echo $app->html;
		
	if( isset( $app->html_footer ) )	
		echo $app->html_footer;
?>	
	</div>
	
<?php
	if( isset( $app->javascript ) )
		echo $app->javascript;
?>	
	</body>
	</html>