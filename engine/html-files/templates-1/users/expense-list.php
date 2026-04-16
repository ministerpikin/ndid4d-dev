<?php 
	if( isset( $data["item"] ) && $data["item"] ){
		$type = "";
		$sval = $data["item"];
		$class = "active";
		$roles = get_access_roles();
		
		include "display-app-view/expense-list.php";
	}
?>