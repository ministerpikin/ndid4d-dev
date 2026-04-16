<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
textarea.form-control {
	width: 100%;
    height: 15em;
}
</style>
<?php
if( isset( $data ) && $data ){
	// echo '<pre>xxxx';print_r( $data );
	$name = $data[ 'user_fname' ] . ' ' . $data[ 'user_lname' ];	
	$mail = $data[ 'user_email' ];	
}
?>
	<div class="row" >
		<div class="col-md-12"> 
			<form name="tickets" id="tickets-form" method="post" action="?action=tickets&todo=save_new_ticket_form&html_replacement_selector=new_ticket_form-container" class="activate-ajax">
				<fieldset>
					<legend>Ticket Information</legend>
					<div class="row">
						<div class="col-md-6">
							<label>Date<sup>*</sup></label>
							<input class="form-control " type="date"  placeholder="" value=""/>
						</div>
						<div class="col-md-6">
							<label>Type<sup>*</sup></label>
							<select class="field_options form-control" value="" key="" id="" name="type">
								<?php
									$a = get_comment_type();

									if( isset( $a ) && is_array( $a ) && ! empty( $a ) ){
										foreach( $a as $key => $value ){
								?>
										<option value="<?php echo $key ?>" ><?php echo $value ?></option>
								<?php
										}
									}
								?>
							</select>
						</div>
					</div>
				</fieldset>
				<br />

				<fieldset>
					<legend>Message</legend>
					<div class="col-md-10 inbox-compose form-horizontal">


					    <div class="inbox-form-group mail-to">
					        <label class="control-label">To:</label>
					        <div class="controls controls-to">
					            <input type="text" class="form-control col-md-12" name="to">
					            <span class="inbox-cc-bcc">
					                <span class="inbox-cc">Cc</span>
					                <span class="inbox-bcc">Bcc</span>
					            </span>
					        </div>
					    </div>
					    <div class="inbox-form-group input-cc display-hide">
					        <a href="javascript:;" class="close"></a>
					        <label class="control-label">Cc:</label>
					        <div class="controls controls-cc">
					            <input type="text" name="cc" class="form-control">
					        </div>
					    </div>
					    <div class="inbox-form-group input-bcc display-hide">
					        <a href="javascript:;" class="close"></a>
					        <label class="control-label">Bcc:</label>
					        <div class="controls controls-bcc">
					            <input type="text" name="bcc" class="form-control">
					        </div>
					    </div>
					    <div class="inbox-form-group">
					        <label class="control-label">Subject:</label>
					        <div class="controls">
					            <input type="text" class="form-control" name="subject">
					        </div>
					    </div>
					    <div class="inbox-form-group">
					        <textarea class="inbox-editor inbox-wysihtml5 form-control" name="message" rows="12"></textarea>
					    </div>


            		</div>
				</fieldset>
				<br />

				<fieldset>
					<legend>Attachments</legend>
					<div class="row">
						<div class="col-md-12">
							<label>Select File</label>
							<?php 
								echo get_file_upload_form_field( array( "field_id" => "supporting_document", "t" => 1, "attributes" => ' skip-uploaded-file-display="1" ' ) );
							?>
						</div>
					</div>
				</fieldset>
				<br />
				
				<div class="row">
					<div class="col-md-3">
						<input class="btn blue" value="Submit Ticket" type="submit" />
					</div>
					<div class="col-md-3">
						<input class="btn dark" value="Discard" type="submit" />
					</div>
					<div class="col-md-3">
						<input class="btn dark" value="Draft" type="submit" />
					</div>
				</div>
			</form>
			<hr />

		</div>
	</div>
</div>