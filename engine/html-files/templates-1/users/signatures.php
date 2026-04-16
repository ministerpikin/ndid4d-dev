<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>';
		$user = isset($data['user']) && $data['user'] ? $data['user'] : '';
		$cb = isset($data['callback']) && $data['callback'] ? $data['callback'] : '';
	?>
	<div class="">
		<div class="">
			<h4><b>Signature</b></h4>
			<form action="?action=users&todo=save_signatures" id="signbox">
				<?php
					$fpagepointer = $pagepointer;
					if( defined("NWP_APP_DIR") && NWP_APP_DIR ){
						$fpagepointer = '../' . NWP_APP_DIR . '/';
					}
					
					echo get_form_headers( array(
						'id' => isset($user['id']) ? $user['id'] : '',
						'table' => 'users',
					) );

					echo '<div class=" form-group control-group input-row">';
						echo '<label for="">Upload Signature: </label>';
						$e = isset($user['data']) && $user['data'] ? json_decode($user['data'], true) : [];
						//print_r($e);
						$e1 = isset($e['file']) && $e['file'] ? $e['file'] : '';
						
						/* if( ! $e1 && isset( $e['image'] ) && $e['image'] ){
							echo '<img src="'. $e['image'] .'" style="border:2px solid #333; max-width:90%; max-height:75px;" /><br />';
						} */
						
						echo get_file_upload_form_field( array( 'field_label' => 'Signature', 'value' => $e1, 'pagepointer' => $fpagepointer, 'field_id' => 'signature_1', 't' => 1, "acceptable_files_format" => 'jpg:::png:::jpeg', "attributes" => ' skip-uploaded-file-display="1" ' ) );
						
						
					echo '</div>';

					echo "<br>";
					
					echo "------ OR -------<br><br>";
					
					echo '<a href="#" class="btn dark custom-single-selected-record-button" title="Click Here to Sign" override-selected-record="'. $user['id'] .'" action="?module=&action=users&todo=capture_signature&html_replacement_selector=user-signature-container">Click Here to Sign</a><div id="user-signature-container"></div>';
				?>
				<br>

				<input type="submit" class="btn blue" value="Save">
			</form>
		</div>
	</div>
	<script>
		var nwSign = {
			signBox: false,
			upload: false,
			setSignBox(){ this.signBox = true; },
		};
		!function(){
			$.fn.uplChange = function(cb) {
			  return $(this).each((_, el) => {
			    new MutationObserver(mutations => {
			      mutations.forEach(mutation => cb && cb(mutation.target));
			    }).observe(el, {
			    	childList: true,
			    });
			  });
			}

			$('.file-content').uplChange(el => {
				if( $(el).find('.file-upload-success').length ){
					nwSign.upload = true;
				}else{
					nwSign.upload = false;
				}
			} );

			$('form#signbox').submit(function(e){
				e.preventDefault();
				if( !(nwSign.upload ^ nwSign.signBox ) ){
					$.fn.cProcessForm.display_notification( {
						typ: 'jsuerror',
						err:  '<h4><b>Invalid Signature</b></h4>',
						msg:  '<p>You can only use one option and not both. Be sure to use one option before submitting the form</p>'
					} );
				}else{
					let cb = '<?php echo $cb ?>';
					$.fn.cProcessForm.ajax_data = {
		               ajax_data: $(this).serializeAndEncode() + (nwSign.signBox ? '&signbox=' + nwSign.signBox : '') + ( cb ? '&cb=' + cb : '' ),
		               form_method: 'post',
		               ajax_data_type: 'json',
		               ajax_action: 'request_function_output',
		               ajax_container: '',
		               ajax_get_url: $(this).attr('action'),
		           };
		           $.fn.cProcessForm.ajax_send();
				}
			})
		}();
	</script>
</div>