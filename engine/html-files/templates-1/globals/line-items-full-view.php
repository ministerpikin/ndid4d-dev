<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="row">
	<div class="col-md-2"> 
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Operators</small></small></div>
				<div class="tools">
				<a href="" class="collapse" ></a>
				<a href="" class="remove"></a>
				</div>
			</div>
			<div class="portlet-body" id="tree-view-selector-container" style="max-height:320px; overflow-y:auto;">
				<div class="input-group">
				<select id="operators-select-tree-view-activator" class="form-control" style="font-size: 12px; padding: 2px 5px; height: 24px; font-weight: bold;">
					<option value="">--- Select Operator ---</option>
					<?php 
						if( isset( $data[ 'operators' ] ) && $data[ 'operators' ] ){
							foreach( $data[ 'operators' ] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
							}
						}
					?>
				</select>
				<div class="input-group-btn">
					<button id="refresh-tree-view" title="Click to Reload Tree View" type="button" class="btn default btn-sm" style="height:24px;"><i class="icon-refresh"></i></button>
				 </div>
				 <!-- /btn-group -->
				</div>
				<div id="ui-navigation-tree-container">
					<div id="ui-navigation-tree" class="demo"></div>
				</div>
			</div>
		</div>
		
		<div class="portlet grey box">
			<div class="portlet-title">
				<div class="caption"><i class="icon-globe"></i><small><small>Data Entry</small></small></div>
				<div class="tools">
				<a href="" class="collapse" ></a>
				<a href="" class="remove"></a>
				</div>
			</div>
			<style type="text/css">
				#tree-view-selector-container{
					overflow-x:auto;
				}
				.line-item-sep{
					margin:5px 0;
				}
				.portlet{
					margin-bottom:5px;
				}
				#data-entry-form-container {
					
				}
				#data-entry-form-container label{
					font-size:10px;
					font-weight:600;
				}
				#data-entry-form-container h3{
					font-size:12px !important;
					font-weight:600;
					margin-top:0;
				}
			</style>
			<div class="portlet-body" id="data-entry-form-container" style="max-height:500px; overflow-y:auto;">
				<?php if( isset( $data['data_entry_form'] ) && $data['data_entry_form'] )echo $data['data_entry_form']; ?>
			</div>
		</div>
	</div>
	<div class="col-md-10" id="main-table-view"> 
		<?php 
			if( isset( $data["html"] ) )echo $data["html"]; 
			include "datatable-record-details-popup.php";
		?>
		<?php //include "line-items-datatable-view.php"; ?>
	</div>
</div>
</div>