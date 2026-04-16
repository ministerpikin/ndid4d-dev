<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	echo '<pre>';print_r( $data );echo '</pre>'; 
?>
<form id="select-data-source-form" class="activate-ajax" action="?action=audit&todo=save_display_data_access_view&html_replacement_selector=data-source-container">

	<div class="row">
		<div class="col-md-3">
			<label>Select Data Source</label>
			<?php 
				$data["databases"] = get_database_tables( array( "group_plugin" => 1 ) );
				if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
					echo '<select name="data_source" class="form-control select2" required>';
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
			?>
		</div>
		<div class="col-md-2">
			<label>Option</label>
			<select name="s_option" class="form-control">
				<option value="datatable">Datatable</option>
				<option value="datatable_trash">Datatable (Trash)</option>
				<option value="spreadsheet">Spreadsheet</option>
				<option value="duplicates">Duplicates</option>
			</select>
		</div>
	</div>
</form>
	<br />
	<br />
<div id="data-source-container">
</div>
</div>
<script type="text/javascript">
	$( 'form#select-data-source-form' ).find( 'select' ).on( 'change', function(){ $( 'form#select-data-source-form' ).submit() });
</script>