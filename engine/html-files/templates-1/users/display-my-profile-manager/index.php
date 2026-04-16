<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php

	if( isset( $data["action_to_performed"] ) ){
		
		switch( $data["action_to_performed"] ){
		case "display_my_password_change":
			$show_more = 0;
			$col_offset = '';
			$default_style = '';
			$show_button = 1;
		break;
		}
		
	}
?>
<div style="margin:20px 0px;">
	<div class="row profile-account">
	   
	   <div class="col-md-8 col-md-offset-2">
	       <h5 class="mb-3">My Profile</h5>
	       <div class="card">
	           <div class="card-body">
	               <!-- Nav tabs -->
	               <ul class="nav nav-pills nav-customs nav-danger mb-3" role="tablist">
	                   <li class="nav-item">
	                       <a class="nav-link active custom-action-button" data-bs-toggle="tab" href="#tab_1-1" role="tab" skip-title="1" title="My Profile Manager" function-name="view_my_profile" function-class="users" function-id="42">Personal info</a>
	                   </li>
	                   <li class="nav-item">
	                       <a class="nav-link custom-action-button" data-bs-toggle="tab" href="#tab_1-1" role="tab" skip-title="1" title="My Profile Manager" function-name="edit_my_profile" function-class="users" function-id="45">Edit Profile</a>
	                   </li>
					   <?php if( defined("EDIT_PROFILE_USERS_HIDE_SIGNATURE") && EDIT_PROFILE_USERS_HIDE_SIGNATURE ){ ?>
	                   <li class="nav-item">
	                       <a class="nav-link custom-action-button" data-bs-toggle="tab" href="#tab_1-1" role="tab" skip-title="1" title="My Signatures" function-name="edit_my_signature" function-class="users" function-id="45">Edit Signature</a>
	                   </li>
					   <?php } ?>
	                   <li class="nav-item">
	                       <a class="nav-link custom-action-button" data-bs-toggle="tab" href="#tab_1-1" role="tab" skip-title="1" title="My Profile Manager" function-name="edit_my_password" function-class="users" function-id="48">Change Password</a>
	                   </li>
	               </ul><!-- Tab panes -->
	               <div class="tab-content">
	                   <div class="tab-pane active" id="tab_1-1" role="tabpanel">

	                   		<div class="row">
	                   		   <div class="col-md-6" id="user-info-page" style1="max-height:480px; overflow-y:auto; overflow-x:hidden;">
	                   			<?php
	                   				if( isset( $data['user_info_html'] ) ){
	                   					echo $data['user_info_html'];							
	                   				}
	                   			?>
	                   		   </div>
	                   		</div>

	                   </div>
	               </div>
	           </div><!-- end card-body -->
	       </div>
	   </div>

	</div>
 </div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>