<div id="copy-bidders">
<div id="form-panel-wrapper">
	<form name="bidders" id="bidders-form" method="post" action="?action=bidders&todo=save_copy_bidders" enctype="multipart/form-data" data-ajax="false" class="login-form activate-ajax">
		
		<div class="form-group control-group input-row -row bidders-row">
			<label class="control-label cell bidders-label">Select Technical Evaluation Bidders to Copy:</label>
			<div class="controls cell-element ">
				<select multiple="multiple" class="form-control form-gen-element demo-input-local" id="bidders" name="bidders[]">
					<?php
						if( isset( $data[ "bidders" ] ) && is_array( $data[ "bidders" ] ) ){
							foreach( $data[ "bidders" ] as $key => $val ){
								?>
								<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
								<?php
							}
						}
					?>
				</select>
			</div>
		</div>
		
		<div class="control-group input-row bottom-row" style="margin-bottom:20px;">
			<div class="controls cell">
				<input id="form-gen-submit" data-loading-text="processing..." class="form-gen-button btn btn-primary blue" value="Copy Selected Bidders to Commercial Stage &rarr;" type="submit">
			</div>
		</div>
	</form>
</div>
</div>