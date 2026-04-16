<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>';
		$form_data = isset( $data['form_data'] ) && $data['form_data'] ? $data['form_data'] : [];
		$show_mod = isset( $data['show_mod'] ) && $data['show_mod'] ? $data['show_mod'] : [];
		$action_to_perform = isset($data['action_to_perform']) && $data['action_to_perform'] ? $data['action_to_perform'] : '';
		$btn_l = 'Add Field';
		switch ($action_to_perform) {
			case 'edit_new_fields':
				$btn_l = 'Save Field';
			break;
		}
	?>

	<div id="add-field-container">

		<?php
			if( $show_mod ){ ?>
				<div class="note note-warning">
					<h4 class=""><b>Index Regeneration Warning</b></h4>
					<p>Making changes to form field type may <span class="text-danger text-uppercase">recreate</span> data source and <span class="text-danger text-uppercase">purge</span> already captured data</p>
				</div>
			<?php }
		?>
		
		<form class="activate-ajax <?php echo ($show_mod ? 'confirm-prompt' : ''); ?>" <?php echo ($show_mod ? 'confirm-prompt="Are you sure you want to save this field"' : ''); ?> action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=save_<?php echo $action_to_perform; ?>&html_replacement_selector=add-field-container">
			
			<input type="hidden" name="dt" value="<?php echo ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '' ); ?>">
			<input type="hidden" name="field_key" value="<?php echo ( isset( $form_data['field_key'] ) && $form_data['field_key'] ? $form_data['field_key'] : ''); ?>">
			<!-- 
				<div class="form-group">
					<label for="">Data Source </label>
					<input type="text" class="form-control" readonly value="">
				</div>
 			-->
			<div class="form-group">
				<label for="">Field Label <sup>*</sup></label>
				<input type="text" required class="form-control" name="field_label" value="<?php echo (isset($form_data['field_label']) && $form_data['field_label'] ? $form_data['field_label'] : '') ?>">
			</div>

			<div class="form-group">
				<label for="">Display Field Label </label>
				<textarea class="form-control" name="display_field_label" id="" cols="30" rows="2"><?php echo ( isset( $form_data['display_field_label'] ) &&  $form_data['display_field_label'] ? $form_data['display_field_label'] : ''  ); ?></textarea>
			</div>

			<div class="form-group">
				<label for="">Form Field Type <sup>*</sup></label>
				<select name="field_type" class="form-control">
				<?php
					// $v = get_form_fields();
					$v = [
						'text' => 'Text Box',
						'textarea' => 'Text Area',
						'select' => 'Select Box',
						// 'multi-select' => 'Multi-Select',
						'date-5' => 'Date',
						'date-5time' => 'Date (Show Time)',
						'time' => 'Time',

						'email' => 'Email',
						'tel' => 'Telephone',
						
						'number' => 'Numbers',
						'decimal' => 'Decimals',
					];
					if( $v ){
						$vz = isset( $form_data['field_type'] ) && $form_data['field_type'] ? $form_data['field_type'] : '';
						foreach( $v as $k => $vl ){ ?> 
							<option <?php  echo ($k == $vz ? 'selected' : ''); ?> value="<?php echo $k; ?>"><?php echo $vl; ?></option> <?php
						} 
					}
				?>
				</select>
			</div>

			<div class="form-group">
				<label for="">Form Field Options </label>
				<input type="text" class="form-control" name="form_field_option" value="<?php echo ( isset($form_data['form_field_option']) && $form_data['form_field_option'] ? $form_data['form_field_option'] : '' ); ?>" placeholder="For Select Form Field Types Only">
				<i>Note: Manage Options Using Listbox Options </i>
			</div>

			<!-- <div class="form-group">
				<label for="">Class</label>
				<input type="text" name="class" class="form-control">
				<i>List Box Key</i>
			</div> -->

			<input type="submit" class="btn btn-primary" value="<?php echo $btn_l; ?>">
		</form>
	</div>
</div>