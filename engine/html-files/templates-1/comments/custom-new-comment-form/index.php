<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
<?php 
	if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; 
?>
input.btn{
	width: 100%;
}
</style>
<?php
$message = ( isset( $data[ 'message' ] ) && $data[ 'message' ] ) ? $data[ 'message' ] : 0;
$hide_fields = ( isset( $data[ 'hide_fields' ] ) && $data[ 'hide_fields' ] ) ? $data[ 'hide_fields' ] : array();
$plugin = ( isset( $data[ 'plugin' ] ) && $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
$reference = ( isset( $data[ 'reference' ] ) && $data[ 'reference' ] ) ? $data[ 'reference' ] : '';
$reference_table = ( isset( $data[ 'reference_table' ] ) && $data[ 'reference_table' ] ) ? $data[ 'reference_table' ] : '';

$ctype = ( isset( $data[ 'more_data' ][ 'type' ] ) && $data[ 'more_data' ][ 'type' ] ) ? $data[ 'more_data' ][ 'type' ] : '';
$ctitle = ( isset( $data[ 'more_data' ][ 'title' ] ) && $data[ 'more_data' ][ 'title' ] ) ? $data[ 'more_data' ][ 'title' ] : '';

$hide_most_recent = ( isset( $data[ 'more_data' ][ 'hide_most_recent' ] ) && $data[ 'more_data' ][ 'hide_most_recent' ] ) ? $data[ 'more_data' ][ 'hide_most_recent' ] : '';

$container_class = ( isset( $data[ 'more_data' ][ 'container_class' ] ) && $data[ 'more_data' ][ 'container_class' ] ) ? $data[ 'more_data' ][ 'container_class' ] : '';

$message_title = ( isset( $data[ 'more_data' ][ 'message_title' ] ) && $data[ 'more_data' ][ 'message_title' ] ) ? $data[ 'more_data' ][ 'message_title' ] : '';
$uhsel = ( isset( $data[ 'preview_html_replacement_selector' ] ) && $data[ 'preview_html_replacement_selector' ] ) ? $data[ 'preview_html_replacement_selector' ] : '';
// echo '<pre>';print_r( $data ); echo '</pre>';

$btn_caption = 'Submit Comment';
if( $message ){
	$btn_caption = 'Send Message';
}

if( $message_title ){
	$btn_caption = 'Save ' . $message_title . ' &rarr;';
}

$count = isset( $data[ 'count' ] ) ? $data[ 'count' ] : 1;
?>
	<div id="new_comment_form-container" class="<?php echo $container_class; ?>">
	
	<div class="row" >
		<div class="col-md-12"> 
			<form name="comments" id="comments-form" method="POST" action="?action=comments&todo=save_new_comment_form&html_replacement_selector=new_comment_form-container" class="activate-ajax form-horizontal">
				<?php if( $uhsel ){ ?><input value="<?php echo $uhsel; ?>" name="preview_html_replacement_selector" style="display:none;"><?php } ?>
				<?php if( $plugin ){ ?><input value="<?php echo $plugin; ?>" name="plugin" style="display:none;"><?php } ?>
				<?php if( $reference ){ ?><input value="<?php echo $reference; ?>" name="reference" style="display:none;"><?php } ?>
				<?php if( $reference_table ){ ?><input value="<?php echo $reference_table; ?>" name="reference_table" style="display:none;"><?php } ?>
				<?php if( $ctype ){ ?><input value="<?php echo $ctype; ?>" name="type" type="hidden"><?php } ?>
				<?php if( $ctitle ){ ?><input value="<?php echo $ctitle; ?>" name="ctitle" type="hidden"><?php } ?>
				<div class="row">
					<div class="col-md-12 email">

						<?php if( !( isset( $hide_fields[ 'tag' ] ) &&  $hide_fields[ 'tag' ] ) ){ ?>
							<fieldset>
								<legend><?php echo $count; ?> Selected Records</legend>
								<div id="selectTag">
									<div class="row">
										<div class="col-md-12">
											<label>Select Folder</label>
											
											<div class="input-group">
												<input class="form-control data-form select2" id="tags_existing" action="?action=tags&todo=get_select2" type="text" name="tags_name_existing" onchange="" />
												
												<span class="input-group-btn">
													<a class="btn btn-smx dark" href="#" onclick="$.nwCommen.newTag(); return false;" title="Create new folder"><i class="icon-folder-close"></i>&nbsp;</a>
												</span>
											</div>
											
										</div>
									</div>
					            	<br />
								</div>

								<div id="newTag" style="display: none;  background: #eee; padding: 10px 15px;  margin: 10px 0;">
									<a href="#" class="nm btn-sm btn-default pull-right" onclick="$.nwCommen.newTag2();">x</a><br />
									<div class="row">
										<div class="col-md-12">
											<label>New Folder Name</label>
											
											<input class="form-control data-form" type="text" name="tags_name" onchange="" />
											
										</div>
									</div>
					            	<br />
								</div>
							</fieldset>
							<br />
							<br />
					    <?php
							}
					    ?>

						<fieldset class=" inbox-compose">
							<legend><?php echo ($message_title)?$message_title:'Message'; ?></legend>
							<?php if( !( isset( $hide_fields[ 'assigned_to' ] ) &&  $hide_fields[ 'assigned_to' ] ) ){ ?>
							    <div class="inbox-form-group mail-to">
							        <label class="control-label">To:</label>
							        <div class="controls controls-to">
									<input class="form-control select2" type="text" placeholder="" action="?module=&action=users&todo=get_select2" name="assigned_to" tags="true" required />
							        </div>
							    </div>
						    <?php
								}
						    ?>

							<?php if( !( isset( $hide_fields[ 'title' ] ) && $hide_fields[ 'title' ] ) ){ ?>
							    <div class="inbox-form-group">
							        <label class="control-label">Subject:</label>
							        <div class="controls">
							            <input type="text" class="form-control" name="title" required>
							        </div>
							    </div>
						    <?php
								}
						    ?>
						    <div class="inbox-form-group">
						        <textarea class="inbox-editor inbox-wysihtml5 form-control" name="message" rows="12"></textarea>
						    </div>
						</fieldset>

            		</div>
        		</div>
        		<br />


				<?php /* if( !( isset( $hide_fields[ 'supporting_document' ] ) && $hide_fields[ 'supporting_document' ] ) ){ ?>
					<div class="row">
						<div class="col-md-12">
							<label>Select File</label>
							<?php 
								echo get_file_upload_form_field( array( "field_id" => "supporting_document", "t" => 1, "attributes" => ' skip-uploaded-file-display="1" ' ) );
							?>
						</div>
					</div>
			    <?php
					}*/
			    ?>
				<br />
				
				<div class="row">
					<div class="col-md-12">
						<input class="btn blue" value="<?php echo $btn_caption; ?>" type="submit" />
					</div>
				</div>
			</form>

		</div>
	</div>
	
	<?php if( ! $hide_most_recent ){ ?>
	<?php if( ! $message ){ ?>
	<hr />
	<h4><strong><a href="#" class="custom-single-selected-record-button" action="?action=comments&todo=comments_search_list2&table=<?php echo $reference_table ?>" override-selected-record="<?php echo $reference; ?>" target="_blank" new_tab="1" title="Most Recent Comment"><i class="icon-external-link"></i> Most Recent Comment</a></strong></h4>
	<div class="row" >
		<div class="col-md-12" id="most-recent-comments"> 
		</div>
	</div>
	<?php } ?>
	<?php } ?>
	
	</div>
</div>
<script type="text/javascript" >
(function($) {
    $.nwCommen = {
        newTag2: function(){
        	$( '#newTag' ).hide();
        	$( '#newTag' ).find( 'input' ).val( "" );
        	$( '#selectTag' ).show();
        	$( '#selectTag' ).find( 'input' ).val( "" );
        },
        newTag: function(){
        	$( '#newTag' ).show();
        	$( '#selectTag' ).hide();
        	$( '#selectTag' ).find( 'input' ).val( "" );

        },
    };
}(jQuery));
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
	Inbox.init();
</script>