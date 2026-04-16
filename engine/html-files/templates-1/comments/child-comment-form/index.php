<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
<?php 
	if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; 
?>
</style>
<?php
	
$fields = isset( $data['fields'] )?$data['fields']:array();
$labels = isset( $data['labels'] )?$data['labels']:array();
$table = isset( $data["table"] )?$data["table"]:"";

$GLOBALS["fields"] = $fields;
$GLOBALS["labels"] = $labels;

$hide_fields = ( isset( $data ) && $data && isset( $data[ 'hide_fields' ] ) && $data[ 'hide_fields' ] ) ? $data[ 'hide_fields' ] : array();
$plugin = ( isset( $data[ 'parent' ]["plugin"] ) && $data[ 'parent' ]["plugin"] ) ? $data[ 'parent' ]["plugin"] : '';
$reference = ( isset( $data[ 'reference' ] ) && $data[ 'reference' ] ) ? $data[ 'reference' ] : '';
$reference_table = ( isset( $data[ 'reference_table' ] ) && $data[ 'reference_table' ] ) ? $data[ 'reference_table' ] : '';
// echo '<pre>';print_r( $data ); echo '</pre>';

if( isset( $data[ 'parent' ]["id"] ) && $data[ 'parent' ]["id"] ){
?>
	
   <div class="portlet">
      
      <div class="portlet-body" id="chats">
         <form id="child-comments-form" method="POST" action="?action=comments&todo=save_child_comment_form&html_replacement_selector=new_comment_form-container" class="activate-ajax form-horizontal">
				<?php if( $plugin ){ ?><input value="<?php echo $plugin; ?>" name="plugin" style="display:none;"><?php } ?>
				<?php if( $reference ){ ?><input value="<?php echo $reference; ?>" name="reference" style="display:none;"><?php } ?>
				<?php if( $reference_table ){ ?><input value="<?php echo $reference_table; ?>" name="reference_table" style="display:none;"><?php } ?>
				<input name="type" value="child" style="display:none;">

				<div class="chat-form">
					<div class="input-cont">   
					   <input class="form-control" type="text" placeholder="Type a message here..." name="message" />
					</div>
					<div class="btn-cont"> 
					   <span class="arrow"></span>
					   <button href="" class="btn blue icn-only" type="submit" value=""><i class="icon-ok icon-white"></i></button>
					</div>
				</div>
         </form>
      </div>
   </div>
<?php } ?>
</div>