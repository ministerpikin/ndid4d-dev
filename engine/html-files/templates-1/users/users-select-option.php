<?php
	//print_r($data["user_info"]);
	if( ( isset( $data["users"] ) && is_array( $data["users"] ) && ! empty( $data["users"] ) ) ){
		foreach( $data["users"] as $sval ){
			?>
			<option value="<?php echo $sval["id"]; ?>"><?php echo $sval["firstname"] . ' ' . $sval["lastname"]; ?></option>
			<?php
		}
	} 
?>