<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	$class = 'col-md-4';
	if( isset( $data["class"] ) && $data["class"] ){
		$class = $data["class"];
	}
	
	$new_reference_value = isset( $data['reference_value'] )?$data['reference_value']:'';
	if( isset( $data['new_reference_value'] ) && $data['new_reference_value'] ){
		$new_reference_value = $data['new_reference_value'];
	}
	
	if( ! ( isset( $data["form_action"] ) && $data["form_action"] ) ){
		$data["form_action"] = '?action='.$data["table"].'&todo=search_customer_call_log';
	}
	
	$form_class = '';
	if( ( isset( $data["form_class"] ) && $data["form_class"] ) ){
		$form_class  = $data["form_class"];
	}
	
	$form_id = $data["table"];
	if( ( isset( $data["form_id"] ) && $data["form_id"] ) ){
		$form_id  = $data["form_id"];
	}
	
	$btn_class = 'btn ';
	if( isset( $data["submit_button_class"] ) && $data["submit_button_class"] ){
		$btn_class .= $data["submit_button_class"];
	}else{
		$btn_class .= ' dark ';
	}
	
	$hide_new_button = 0;
	if( isset( $data["hide_new_button"] ) && $data["hide_new_button"] ){
		$hide_new_button = $data["hide_new_button"];
	}
	
	$hide_date_button = 0;
	if( isset( $data["hide_date_button"] ) && $data["hide_date_button"] ){
		$hide_date_button = $data["hide_date_button"];
	}
?>
<form class="activate-ajax <?php echo $form_class; ?> skip_show_headers" method="post" id="<?php echo $form_id; ?>" action="<?php echo $data["form_action"]; ?>">
<?php if( isset( $data['reference'] ) && $data['reference'] ){ ?>
<input type="hidden" name="<?php echo $data['reference']; ?>" class="get-customer-id" value="<?php echo isset( $data['reference_value'] )?$data['reference_value']:''; ?>" />
<?php } ?>
<?php 
	if( isset( $data['hidden_fields'] ) && is_array( $data['hidden_fields'] ) && !empty( $data['hidden_fields'] ) ){
		foreach( $data['hidden_fields'] as $field => $value ){
			?>
			<input type="hidden" name="<?php echo $field; ?>" value="<?php echo $value; ?>" />
			<?php
		}
	}
?>
<div class="row">
<?php 
	if( isset( $data["select_field"] ) && is_array( $data["select_field"] ) && ! empty( $data["select_field"] ) ){
		foreach( $data["select_field"] as $field ){
			$type = isset( $field["type"] )?$field["type"]:'';
			switch( $type ){
			case "select":
			?>
			<div class="<?php echo $class; ?>">
				<select class="form-control <?php echo isset( $field["class"] )?$field["class"]:''; ?>" refresh-form="<?php echo ( isset( $field["refresh_form"] )?$field["refresh_form"]:'' ); ?>" action="<?php echo isset( $field["action"] )?$field["action"]:''; ?>" placeholder="<?php echo $field["placeholder"]; ?>" name="<?php echo $field["name"]; ?>" value="<?php echo isset( $field["value"] )?$field["value"]:''; ?>" label="<?php echo isset( $field["value_label"] )?$field["value_label"]:''; ?>" <?php echo isset( $field["attributes"] )?$field["attributes"]:''; ?>>
					<?php 
						if( isset( $field["option"] ) && is_array( $field["option"] ) && !empty( $field["option"] ) ){
							foreach( $field["option"] as $k => $v ){
							?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php
							}
						}
					?>
				</select>
			</div>
			<?php
			break;
			default:
				?><div class="<?php echo $class; ?>"><?php
					include "app-fields-filter-control-input.php";
				?></div><?php
			break;
			}
		}
	}
?>
<?php if( ! $hide_date_button ){ ?>
<div class="<?php echo $class; ?>">
	<div class="input-group">
	<span class="input-group-addon" style="color:#777;">From</span>
	 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-m-01"); ?>" />
	</div>
</div>
<div class="<?php echo $class; ?>">
	<div class="input-group">
	 <span class="input-group-addon" style="color:#777;">To</span>
	 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
	</div>
</div>
<?php } ?>
<?php if( ( isset( $data["submit_button_label"] ) && $data["submit_button_label"] ) || ! $hide_new_button ){  ?>
<div class="<?php echo $class; ?>">
	<button class="<?php echo $btn_class; ?>" type="submit"><?php echo $data["submit_button_label"]; ?></button>
	
	<?php 
	if( ! $hide_new_button ){ 
		if( isset( $data["buttons"] ) && is_array( $data["buttons"] ) && ! empty( $data["buttons"] ) ){
			foreach( $data["buttons"] as $button ){
				?>
				<button class="btn <?php echo $button["class"]; ?>" action="?module=&action=<?php echo $button["table"]; ?>&todo=<?php echo $button["action"]; ?>" override-selected-record="<?php echo $button["value"]; ?>" title="<?php echo $button["title"]; ?>" href="#"><?php echo $button["label"]; ?></button>
				<?php
			}
		}
	}
	?>
</div>
<?php } ?>
</div>
</form>
</div>