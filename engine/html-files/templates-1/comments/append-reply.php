<?php
// print_r( $data["items"] ); exit;

if( isset( $data[ 'items' ] ) && is_array( $data[ 'items' ] ) && ! empty( $data[ 'items' ] ) ){
	
	$pr = get_project_data();
	$site_url2 = isset( $pr["domain_name"] )?$pr["domain_name"]:'';
	
	$pp = isset( $pagepointer2 )?$pagepointer2:$pagepointer;
	
	if( defined("IPIN_APP_DEFAULT_AVATAR") && IPIN_APP_DEFAULT_AVATAR ){
		$pic1 = IPIN_APP_DEFAULT_AVATAR;
	}else{
		$pic1 = 'files/resource_library/images/avatars/2.jpg';
	}
	$pic = get_uploaded_files( $pp , $pic1, '', '', array( "return_link" => 1 ) );
	
	foreach( $data[ 'items' ] as $item ){
		$user2 = get_name_of_referenced_record( array( "id" => $item[ 'created_by' ], "table" => 'users', "return_data" => 1 ) );
?>
<li class="out">
	<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	  <img class="avatar img-responsive" alt="" src="<?php echo ( isset( $user2[ 'photograph' ] ) && $user2[ 'photograph' ] && $user2[ 'photograph' ] !== 'none' && file_exists( $pp . $user2[ 'photograph' ] ) ) ? ( get_uploaded_files( $pp , $user2[ 'photograph' ], '', '', array( "return_link" => 1 ) ) ) : $pic; ?>" />
	  <div class="message">
		 <span class="arrow"></span>
		 <a href="#" class="name"><?php if( isset( $user2["name"] ) )echo $user2["name"]; ?></a>
		 <span class="datetime">at <?php echo date("d  M Y : h:ia", $item[ 'creation_date' ]); ?></span>
		 <span class="body">
		 <?php echo nl2br( $item[ 'message' ] ); ?>
		 </span>
	  </div>
	</div>
</li>
<?php 
	} 
} 
?>