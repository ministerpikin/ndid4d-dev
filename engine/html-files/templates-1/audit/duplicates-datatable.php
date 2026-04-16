<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	// echo '<pre>';print_r( $data );echo '</pre>'; 
	$tb = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
	$pl = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
	$opt = isset( $data[ 'field_options' ] ) ? $data[ 'field_options' ] : array();
?>

<div class="row tab-contentx">
	<div class="col-md-3">
		
		<form class="search-mode-form activate-ajax" no_submit='1' method="post" id="search-category" action="?action=audit&todo=process_duplicates&html_replacement_selector=duplicts-container">
			<input type="hidden" name="table" value="<?php echo $tb ?>">
			<input type="hidden" name="plugin" value="<?php echo $pl ?>">
			<div class="row">
				<div class="col-md-12">
					<label>Fields <sup>*</sup></label>
					<?php 
					$status_selected = '';
					if( is_array( $opt ) && ! empty( $opt ) ){ ?>
						 <select name="field" required="required" class="form-control select2">
							<option value=""></option>
							<?php foreach( $opt as $key => $val ){ $sel = ''; if( $status_selected == $key )$sel = ' selected="selected" '; ?>
							<option value="<?php echo $key; ?>" <?php echo $sel; ?>><?php echo $val; ?></option>
							<?php } ?>
						 </select>
					<?php } ?>
				</div>
			</div>
			<br>

			<div class="row">
				<div class="col-md-12">
					<label>Limit <sup>*</sup></label>
					 <input type="number" name="limit" value="2" required class="form-control" max="50" />
				</div>
			</div>
			<br>

			<div class="row">
				<div class="col-md-12">
					<input type="submit" class="btn btn-lg1 btn-block green" value="Process Duplicate" />
				</div>
			</div>
			<br>

		</form>
	</div>
	<div class="col-md-9" id="duplicts-container">
	</div>
</div>

<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>