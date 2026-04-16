<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	echo '<pre>'; print_r( $data ); echo '</pre>';
	
	if( isset( $data["event"]["import_template_type"] ) && $data["event"]["import_template_type"] ){
		
		$extra_fields = '';
		
		switch( $data["event"]["import_template_type"] ){
		case "pay_row":
			$extra_fields = '';
		break;
		}
		
		?>
		<form class="activate-ajax" method="post" action="?module=&action=import_items&todo=save_update_import2">
		
		<div class="row">
		<div class="col-md-3">
		
		<label>Matching Fields <sup>*</sup></label>
		<select required="required" class="form-control" name="fields[]" multiple="multiple">
			<option value="0">Minor Build</option>
			<option value="1">Major Build</option>
		</select>
		<br />
		<?php echo $extra_fields; ?>
		<input type="submit" value="Begin Import and Update &rarr;" class="btn blue" />
			
		</div>
		</div>
		
		</form>
		<?php
	}else{
		?>
		<div class="note note-danger"><h4>Invalid Import Template</h4></div>
		<?php
	}
?>
</div>