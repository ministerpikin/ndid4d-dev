<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	$pr = get_project_data();
	$params = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
	
	//echo '<pre>'; print_r($data); echo '</pre>';
	$did = rand();
	$hash = get_file_hash( array( "hash" => 1, "file_id" => $did, "date_filter" => 'd-M-Y' ) );
	
?>
<div class="row">
	<div class="col-md-9 col-md-offset-1">
		<h4><strong><?php if( isset( $data["title"] ) )echo $data["title"]; ?></strong></h4>
		<hr />
		<div class="row" >
			<?php if( isset( $data["show_export"] ) && $data["show_export"] ){ ?>
			<div class="col-md-4">
				<div class="note note-warning">
					<h4 class="block">Export Database</h4>
					<p>
					   Export the current database
					</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="1" action="?action=audit&todo=export_db<?php echo $params; ?>" confirm-prompt="Export Database">Export Database</a>
				 </div>
			</div>
			<?php } ?>
			<div class="col-md-4 col-md-offset-4x">
				<div style="max-height:350px; overflow-y:auto; background:#000; color:#fff; padding:10px;">
				<?php 
					if( isset( $data["exported_databases"] ) && ! empty( $data["exported_databases"] ) ){
						if( isset( $data["show_export"] ) && $data["show_export"] ){
						?>
						<a class="btn btn-sm btn-default custom-single-selected-record-button pull-right" override-selected-record="1" style="color:#333;" action="?action=audit&todo=open_backup_directory<?php echo $params; ?>" title="Click to Open DB Back-up Directory">DB Back-up Directory</a>
						<br />
						<?php
						}
						?>
						<ol>
						<?php
						foreach( $data["exported_databases"] as $sval ){
							
							$cn_path = 'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $sval["dir"] . $sval["basename"], "key" => $hash ) ) .'&dtable='. get_file_hash( array( "encrypt" => "dump", "key" => $hash ) ) .'&name='. rawurlencode( $sval["filename"] );
							?>
							<li><a href="<?php echo $pr["domain_name"] . $cn_path; ?>" target="_blank" style="color:#fff;" title="Click to Download this Back-up"><?php echo $sval["filename"]; ?></a><br /><br /></li>
							<?php
						}
						?>
						</ol>
						<?php
					}
				?>
				</div>
			</div>
			
			<div class="col-md-4 col-md-offset-4x">
				<div style="max-height:350px; overflow-y:auto; background:#000; color:#fff; padding:10px;">
				<?php 
					if( isset( $data["exported_files"] ) && ! empty( $data["exported_files"] ) ){
						if( isset( $data["show_export"] ) && $data["show_export"] ){
						?>
						<a class="btn btn-sm btn-default custom-single-selected-record-button pull-right" override-selected-record="1" style="color:#333;" action="?action=audit&todo=open_backup_directory<?php echo $params; ?>&dir=dump-files" title="Click to Open Files Back-up Directory">Files Back-up Directory</a>
						<br />
						<?php
						}
						?>
						<ol>
						<?php
						foreach( $data["exported_files"] as $sval ){
							
							$cn_path = 'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $sval["dir"] . $sval["basename"], "key" => $hash ) ) .'&dtable='. get_file_hash( array( "encrypt" => "dump-files", "key" => $hash ) ) .'&name='. rawurlencode( $sval["filename"] );
							
							?>
							<li><a href="<?php echo $pr["domain_name"] . $cn_path; ?>" target="_blank" style="color:#fff;" title="Click to Download this Back-up"><?php echo $sval["filename"]; ?></a><br /><br /></li>
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