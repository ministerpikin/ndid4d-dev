<?php
	$trans = 'fade';
	$trans2 = '';
	$theme = '';
	if( defined("HYELLA_THEME") ){
		$theme = HYELLA_THEME;
	}
	
	switch( $theme ){
	case "v3":
		$trans = 'flip';
		if( isset( $data["modal_dialog_static"] ) ){
			if( $data["modal_dialog_static"] ){
				$trans2 = ' data-bs-backdrop="static" ';
			}
		}else if( isset( $modal_body ) && strpos( $modal_body, "activate-ajax" ) > -1 && strpos( $modal_body, "form" ) > -1 ){
			$trans2 = ' data-bs-backdrop="static" ';
		}
		if( defined("ALL_STATIC_MODAL") ){
			$trans2 = '';
			if( ALL_STATIC_MODAL ){
				$trans2 = ' data-bs-backdrop="static" ';
			}
		}
	break;
	}
?>
<div id="<?php if( isset( $modal_id ) )echo $modal_id; else echo "myModal"; ?>" callback="<?php echo ( isset( $data["modal_callback"] )?$data["modal_callback"]:'' ); ?>" class="modal <?php echo $trans; ?>" <?php echo $trans2; ?> tabindex="-1" data-replace="true">
	<div class="modal-dialog <?php if( isset( $data["modal_dialog_class"] ) )echo $data["modal_dialog_class"]; ?>" style="<?php if( isset( $data["modal_dialog_style"] ) )echo $data["modal_dialog_style"]; ?>">
	   <div class="modal-content">
		  <?php
			switch( $theme ){
			case "v3":
			?>
			<div class="modal-header" style="background:#e5e5e5;">
				 <h4 class="modal-title" style="margin-bottom: 12px;"><?php if( isset( $modal_title ) )echo $modal_title; ?></h4>
				 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-top:-20px; float: right;"></button>
			</div>
			<?php
			break;
			default:
			?>
			<div class="modal-header" style="background:#222; color:#fff;">
				 <button type="button" style="color:#fff;" class="close" data-dismiss="modal" title="Click here to close the modal box" aria-hidden="true"><i class="icon-remove" style="color:#fff;"></i></button>
				 <h4 class="modal-title"><?php if( isset( $modal_title ) )echo $modal_title; ?></h4>
			  </div>
			<?php
			break;
			}
		?>
		  
		  <div class="modal-body" >
			<?php
				echo nw_get_breadcrum( array( "type" => "modal" ) );
			?>
			  <div id="modal-replacement-handle">
				 <?php if( isset( $modal_body ) )echo $modal_body; ?>
			  </div>
		  </div>
		  <div class="modal-footer" >
             <button id="modal-popup-close" type="button" class="btn btn-danger" title="Click here to close the modal box" data-dismiss="modal" data-bs-dismiss="modal"><?php if( isset( $modal_finish_caption ) )echo $modal_finish_caption; else echo "Finish"; ?></button>
		  </div>
	   </div>
	</div>
</div>