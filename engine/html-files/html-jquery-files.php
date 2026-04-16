<script type="text/javascript" src="<?php echo $pagepointer; ?>assets/plugins/jquery-1.10.2.min.js"></script>

<?php   
	//INCLUDE JQUERY LIB
	if(isset($jqueries_lib[$page_id]) && is_array(($jqueries_lib[$page_id]))){
		$my_js = $jqueries_lib[$page_id];
		
		foreach($my_js as $require){
			if(file_exists($pagepointer.$require))
				echo '<script src="'.$pagepointer.$require.'" type="text/javascript"></script>';
		}
	}
?>