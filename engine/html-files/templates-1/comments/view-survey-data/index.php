<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="row">
	<div class="col-md-12">
		<h4 class="data-form-title"><?php echo isset( $data['title'] )?$data['title']:''; ?></h4><br />
	</div>
</div>
<?php 
	$table_fields = isset( $data['table_fields'] )?$data['table_fields']:array();
	$table_labels = isset( $data['table_labels'] )?$data['table_labels']:array();
	
	$GLOBALS["fields"] = $table_fields;
	$GLOBALS["labels"] = $table_labels;
	
?>
<div class="row">
	<div class="col-md-12">
		<form class="activate-ajax" method="post" action="?action=survey_data&todo=search_survey_data&html_replacement_selector=survey-data-container">
			<?php
				$f = array();
				if( isset( $data["hidden_fields"] ) && is_array( $data["hidden_fields"] ) && ! empty( $data["hidden_fields"] ) ){
					$f["hidden_fields"] = $data["hidden_fields"];
				}
			?>
			<textarea style="display:none;" name="data"><?php echo json_encode( $f ); ?></textarea>
			<input type="submit" value="Go" class="btn btn-sm dark" />
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="survey-data-container">
	</div>
</div>
<script type="text/javascript" class="auto-remove">
	<?php 
		if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; 
	?>
</script>

</div>