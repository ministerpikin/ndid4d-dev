<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$parent_class1 = 'col-md-12';
	$parent_class2 = 'col-md-12';
	
	if( isset( $data["parent_class1"] ) && $data["parent_class1"] ){
		$parent_class1 = $data["parent_class1"];
	}
	
	if( isset( $data["parent_class2"] ) && $data["parent_class2"] ){
		$parent_class2 = $data["parent_class2"];
	}
	
	$class = 'col-md-4';
	if( isset( $data["class"] ) && $data["class"] ){
		$class = $data["class"];
	}
	
	$class_date = $class;
	if( isset( $data["class_date"] ) && $data["class_date"] ){
		$class_date = $data["class_date"];
	}
	
	$new_reference_value = isset( $data['reference_value'] )?$data['reference_value']:'';
	if( isset( $data['new_reference_value'] ) && $data['new_reference_value'] ){
		$new_reference_value = $data['new_reference_value'];
	}
	
	$form_action = '?action='.$data["table"].'&todo=search_customer_call_log';
	if( isset( $data["form_action"] ) && $data["form_action"] ){
		$form_action = $data["form_action"];
	}
	
	$btn_class = 'btn dark ';
	if( isset( $data["submit_button_class"] ) && $data["submit_button_class"] ){
		$btn_class .= $data["submit_button_class"];
	}
	
	$hide_new_button = 0;
	if( isset( $data["hide_new_button"] ) && $data["hide_new_button"] ){
		$hide_new_button = $data["hide_new_button"];
	}
?>
<div class="row">
<div class="<?php echo $parent_class1; ?>">
<form class="activate-ajax refresh-form" method="post" id="<?php echo $data["table"]; ?>" action="<?php echo $form_action; ?>">
<input type="hidden" name="<?php if( isset( $data['reference'] ) )echo $data['reference']; ?>" class="get-customer-id" value="<?php echo isset( $data['reference_value'] )?$data['reference_value']:''; ?>" />
<?php 
	if( isset( $data['hidden_fields'] ) && is_array( $data['hidden_fields'] ) && !empty( $data['hidden_fields'] ) ){
		foreach( $data['hidden_fields'] as $field => $value ){
			?>
			<input type="hidden" name="<?php echo $field; ?>" value="<?php echo $value; ?>" />
			<?php
		}
	}
?>
<br />
<div class="row">
<?php 
	if( isset( $data["select_field"] ) && is_array( $data["select_field"] ) && ! empty( $data["select_field"] ) ){
		foreach( $data["select_field"] as $field ){
			$type = isset( $field["type"] )?$field["type"]:'';
			
			$dclass = isset( $field["container_class"] )?$field["container_class"]:$class;
			
			switch( $type ){
			case "select":
			?>
			<div class="<?php echo $dclass; ?>">
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
			?>
			<div class="<?php echo $dclass; ?>">
				<input class="form-control <?php echo isset( $field["class"] )?$field["class"]:''; ?>" refresh-form="<?php echo ( isset( $field["refresh_form"] )?$field["refresh_form"]:'' ); ?>" action="<?php echo isset( $field["action"] )?$field["action"]:''; ?>" placeholder="<?php echo $field["placeholder"]; ?>" name="<?php echo $field["name"]; ?>" value="<?php echo isset( $field["value"] )?$field["value"]:''; ?>" label="<?php echo isset( $field["value_label"] )?$field["value_label"]:''; ?>" <?php echo isset( $field["attributes"] )?$field["attributes"]:''; ?> />
			</div>
			<?php
			break;
			}
		}
	}
	
	$sdate = date("U") - (3600 * 24 * 30 * 6);
?>
<div class="<?php echo $class_date; ?>">
	<div class="input-group">
	<span class="input-group-addon" style="color:#777;">From</span>
	 <input type="date" name="start_date" class="form-control re-submit-form" value="<?php if( isset( $data["start_date"] ) && $data["start_date"] )echo $data["start_date"]; else echo date("Y-m-01", $sdate); ?>" />
	</div>
</div>
<div class="<?php echo $class_date; ?>">
	<div class="input-group">
	 <span class="input-group-addon" style="color:#777;">To</span>
	 <input type="date" name="end_date" class="form-control re-submit-form" value="<?php echo date("Y-12-31"); ?>" />
	</div>
</div>
<div class="<?php echo $class; ?>">
	<button class="<?php echo $btn_class; ?>" type="submit">Search</button>
	<?php if( ! $hide_new_button ){ ?>
	<button class="btn blue populate-with-selected custom-single-selected-record-button" action="?module=&action=<?php echo $data["table"]; ?>&todo=new_popup_form" override-selected-record="<?php echo $new_reference_value; ?>" title="<?php echo $data["title"]; ?>" href="#"><?php echo $data["label"]; ?></button>
	<?php } ?>
</div>
</div>
</form>
<hr />

</div>
<div class="<?php echo $parent_class2; ?>">

<div id="<?php echo $data["table"]; ?>-record-search-result" class="resizable-heightX auto-scroll">

</div>

</div>
</div>
<script type="text/javascript" class="auto-remove">
	var g_site_url = "<?php echo $site_url; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>