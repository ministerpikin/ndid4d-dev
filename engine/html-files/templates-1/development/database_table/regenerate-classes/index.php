<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
// echo '<pre>';print_r( $data );echo '</pre>'; 

if( isset( $data[ 'classes' ] ) && $data[ 'classes' ] && ! empty( $data[ 'classes' ] ) ){
?>
	<div class="row" id="regenerate-classes-container">
		<div class="col-md-12">
		<form method="post" class="activate-ajax" id="" action="?module=&action=database_table&todo=upgrade_old_class&html_replacement_selector=regenerate-classes-container" >
			<div class="row">
				<div class="col-md-12">
					<label class="form-element-required-label-yes">Classes</label>
					<?php 
				$data["databases"] = get_database_tables( array( "group_plugin" => 1 ) );
				if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
					echo '<select name="classes[]" class="form-control select2" multiple="multiple" style="height: 27em;" data-type="multi-select" required>';
					foreach( $data["databases"] as $dbk1 => $dbv1 ){
						if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
							echo '<optgroup label="'. $dbv1["label"] .'">';
							foreach( $dbv1["classes"] as $dbk => $dbv ){
								
								echo '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .'">'. $dbv .'</option>';
							}
							echo '</optgroup>';
						}
					}
					echo '</select>';
				} 
				/*
			?>
					<select multiple="multiple" class="form-control" required="required" name="classes[]" style="height: 27em;" data-type="multi-select">
					<?php 
						foreach( $data[ 'classes' ] as $key => $value ){
							echo '<option value="'. $key .'">'. $value[ 'table_name' ] .' ('. $value[ 'label' ] .')</option>';
						}
					?>
					</select> */ ?>
				</div>
			</div>
			<br />

			<div class="row">
				<div class="col-md-12">
					<input type="submit" class="btn blue" value="Re-Generate Classes" />
				</div>
			</div>
			
		</form>
		</div>

	</div>
<?php } ?>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>