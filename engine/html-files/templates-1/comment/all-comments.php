<dl>
<?php
	if( isset( $data["comments"] ) && is_array( $data["comments"] ) && $data["comments"] ){
		foreach( $data["comments"] as $k => $v ){
			if( isset( $v["comment"] ) && $v["comment"] ){
		?>
	
	<dt><?php echo $v["comment"]; ?></dt>
	<dd><i class="icon-time"></i> <?php $key = "creation_date"; if( isset( $v[ $key ] ) && $v[ $key ] )echo date( "d-M-Y H:i", doubleval( $v[ $key ] ) ); ?></dd>
	<dd><i class="icon-user"></i> <?php $key = "created_by"; if( isset( $v[ $key ] ) && $v[ $key ] ){
		$d = get_site_user_details( array( "id" => $v[ $key ] ) );
		if( isset( $d["firstname"] ) && isset( $d["lastname"] ) ){
			echo $d["firstname"] . " " . $d["lastname"];
		}
	}?></dd>
	<hr />
	
	<?php
			}
		}
	}
?>
</dl>