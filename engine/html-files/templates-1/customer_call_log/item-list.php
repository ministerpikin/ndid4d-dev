<?php 
	if( isset( $data["items"] ) && is_array( $data["items"] ) ){
		if( ! empty( $data["items"] ) ){
			include "display-app-view/expense-function.php";
			__expenses( $data["items"] , "" );
		}
	}else{
		if( isset( $data["item"] ) && $data["item"] ){
			$type = "";
			$sval = $data["item"];
			$class = "active";
			
			$types = get_type_of_vendor();
			
			include "display-app-view/expense-list.php";
		}
	}
?>