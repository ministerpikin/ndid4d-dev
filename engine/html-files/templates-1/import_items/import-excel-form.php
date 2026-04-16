<div id="excel-import-form-container-outer">
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	#excel-import-form-container .input-row{
		padding-left:0px !important;
		padding-right:5px !important;
	}
	.bottom-row{
		clear:both;
	}
</style>
<div class="row">
	<?php 
		$cls = 'col-md-5';
		$view = '';
		if( isset( $data['option'] ) && $data['option'] ){ 
			$view = $data['option'];
		}
		
		if( isset( $data['view'] ) && $data['view'] ){
			$view = $data['view'];
		}
		
		switch( $view ){
		case 'full':
		case 'map':
			$cls = 'col-md-12';
		break;
		}
	?>
	<div class="<?php echo $cls; ?>">

		<?php if( $view != 'full' ){ ?>
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><small><?php if( isset( $data["title"] ) && $data["title"] ) echo $data["title"]; if( isset( $data["import_type"] ) && $data["import_type"] ) echo ": ".$data["import_type"]; ?></small></div>
			</div>
		<?php } ?>
			<div class="portlet-body resizable-heightX" id="excel-import-form-container">
				<div class="row" >
					<div class="col-md-12">
						<?php 
							if( $view != 'full' ){
								if( isset( $data["terms"] ) && is_array( $data["terms"] ) && ! empty( $data["terms"] ) ){
									foreach( $data["terms"] as $t => $v ){
										echo '<label>'.$t.'</label><pre><strong>'.$v.'</strong></pre>';
									}
								} 
							} 
						?>
					</div>
				</div>
				<div class="row" >
					<div class="col-md-12">
						<?php
						if( isset( $data['excel_import_form'] ) && $data['excel_import_form'] ){
							echo $data['excel_import_form']['html'];
						}
						?>
					</div>
					<div class="col-md-12">
						<?php
						if( isset( $data['direct_import_form'] ) && $data['direct_import_form'] ){
							echo $data['direct_import_form']['html'];
						}
						?>
					</div>
				</div>
			</div>
		
		<?php if( $view != 'full' ){ ?>
		</div>
		<?php } ?>
		<div style="display:none;">
			<a href="#" class="custom-action-button " month-id="-" budget-id="-" function-id="1" function-class="units" function-name="get_departmental_units" id="dept-units">
				Get Departmental Units
			</a>
		</div>

	</div>
	<div class="col-md-6" id="how-to-import-guide">
		
	</div>
</div>

</div>
</div>