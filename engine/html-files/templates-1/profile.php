<?php
	//Page Body Header
	require_once $pagepointer."html-files/html-body-tag-header.php"; 
	
	//Page Content
?>
	<section class="content">
		<aside class="left fixed-pictures left-side-images">
		</aside>
		
		<article class="width-center margin-auto">
			<div id="main-content">
<?php
				if( isset( $returned_data->typ ) && isset( $returned_data->err ) ){
					echo $returned_data->err . '<br /><br />' . $returned_data->msg;
					
					if( isset( $returned_data->inf ) ){
						echo '<br /><br />'. $returned_data->inf;
					}
				}
				
				if( isset( $returned_data->html ) )
					echo $returned_data->html;
					
?>	
			</div>
		</article>
		
		<aside class="right fixed-pictures right-side-images">
		</aside>
	</section>
<?php		
	//Page Body Footer
	require_once $pagepointer."html-files/html-body-tag-footer.php"; 
	
	//Jquery Files
	require_once $pagepointer."html-files/html-jquery-files.php"; 
?>