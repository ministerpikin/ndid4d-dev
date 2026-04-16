<div id="nw-second-level-auth-container">
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
if( isset( $data["id"] ) && $data["id"] ){
?>
<div style="position: fixed; width: 100%;  height: 100%;  top: 0;   left: 0;  right: 0;  bottom: 0;  background-color: rgba(0,0,0,0.5);   z-index: 90000;   cursor: pointer;"> <div style="position: absolute;
  top: 50%; left: 50%; transform: translate(-50%,-50%); -ms-transform: translate(-50%,-50%);   width: 400px;   background: #fff;  padding: 50px; box-shadow: 2px 4px 2px #4e4e4e;">
	<form class="activate-ajax" method="post" action="?action=authentication&todo=save_second_level_auth_form">
		<input type="hidden" name="id" value="<?php echo $data["id"]; ?>" class="form-control"/>
		<h4><strong>2nd Level Authentication</strong></h4>
		<p><i>please provide your password below</i></p>
		<div class="row">
			<div class="col-md-12">
				<label>User</label>
				<input type="text" disabled="disabled" readonly="1" value="<?php if( isset( $user_info["user_full_name"] ) )echo $user_info["user_full_name"]; ?>" class="form-control"/>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-md-12">
				<label>Password <sup>*</sup></label>
				<input type="password" required="required" name="password" value="" class="form-control"/>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-md-6">
			<input type="submit" value="Submit &rarr;" class="btn-block btn blue"/>
			</div>
			<div class="col-md-6">
				<a href="#" class="btn-block btn dark custom-single-selected-record-button" override-selected-record="<?php echo $data["id"]; ?>" action="?action=authentication&todo=cancel_second_level_auth_form">Cancel</a>
			</div>
		</div>
	</form>
  </div></div>
 <?php } ?>
</div>
</div>