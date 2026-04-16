<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
//echo '<pre>';print_r( $data["html_replacement_selector"] );echo '</pre>';
 $data["html_replacement_selector"] = 'exported-update-con';
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1 pos-v1">
		<h4 class="card-title-1"><strong><?php if( isset( $data["title"] ) )echo $data["title"]; ?></strong></h4>
		<hr />
		
		<div class="row">
			<div class="col-md-3">
			
				<form class="activate-ajax" method="post" action="?module=&action=audit&todo=save_export_updated_files<?php if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] )echo '&html_replacement_selector=' . $data["html_replacement_selector"]; ?>">
					
					
					<label>Date <sup>*</sup></label>
					<input type="datetime-local" required="required" class="form-control" name="start_date" value="<?php echo date("Y-m-d"); ?>" />
					<br />
					
					<label>Build Type <sup>*</sup></label>
					<select required="required" class="form-control" name="major">
						<option value="1">Major Build</option>
						<option value="0">Minor Build</option>
					</select>
					<br />
					
					<input type="submit" value="Begin Export &rarr;" class="btn blue" />
					
				</form>
		
			</div>
			<div class="col-md-3" id="exported-update-con">
			
			</div>
		</div>
		
	</div>
</div>
</div>