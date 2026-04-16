<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<br />
<div class="row">
	<div class="col-md-9 col-md-offset-1">
		<h4>
			<a href="#" class="btn btn-default btn-xs custom-single-selected-record-button" action="?module=&action=audit&todo=display_application_update_view" override-selected-record="1" mod="1" title="Update Application"><i class="icon-refresh"></i></a>
			<strong><?php if( isset( $data["title"] ) )echo $data["title"]; ?></strong>
		</h4>
		<hr />
		<div class="row" >
			<div class="col-md-4">
				<div class="note note-warning">
					<h4 class="block">Internet Based Update</h4>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button-old" override-selected-record="-" disabled="disabled" action="?todo=application_update_function&action=audit">Check the Internet</a>
				 </div>
			</div>
			
			<div class="col-md-4">
				<div class="note note-warning">
					<h4 class="block">Offline Update</h4>
					<?php if( isset( $data[ 'upload_form' ] ) ) echo $data['upload_form']; ?>
				 </div>
			</div>
			
			
			<div class="col-md-4">
				<div style="max-height:350px; overflow-y:auto; background:#000; color:#fff; padding:10px;">
				<h4>Old Version(s)</h4>
				<?php 
					if( isset( $data["versions"] ) && ! empty( $data["versions"] ) ){
						$pr = get_project_data();
						/*
						?>
						<a class="btn btn-sm btn-default custom-action-button pull-right" funtion-id="1" function-name="open_backup_directory" function-class="audit" title="Click to Open Back-up Directory">Back-up Directory</a>
						<?php */ ?>
						<br />
						<ol>
						<?php
						foreach( $data["versions"] as $sval ){
							?>
							<li><a href="<?php echo $pr["domain_name"] . 'tmp/version-history/' . $sval["basename"]; ?>" target="_blank" style="color:#fff;" title="Click to Download this Back-up"><?php echo $sval["filename"]; ?></a><br /><br /></li>
							<?php
						}
						?>
						</ol>
						<?php
					}
				?>
				</div>
			</div>
			
		</div>
	</div>
</div>
</div>