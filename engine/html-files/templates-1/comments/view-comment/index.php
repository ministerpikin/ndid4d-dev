<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="comments-view-details">
<style type="text/css">
<?php 
	if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; 
?>
</style>
<?php
	
$fields = isset( $data['fields'] )?$data['fields']:array();
$labels = isset( $data['labels'] )?$data['labels']:array();
$table = isset( $data["other_params"]["tablex"] )?$data["other_params"]["tablex"]:"";
$limit = isset( $data["limit"] )?$data["limit"]:array();
$html_replacement_selector = "inventories-asset-center-sub";

$GLOBALS["fields"] = $fields;
$GLOBALS["labels"] = $labels;

$ref = '';
$ref_table = '';

//echo '<pre>'; print_r( $data ); echo '</pre>';
if( isset( $data["other_params"][ 'parent' ] ) && ! empty( $data["other_params"][ 'parent' ] ) ){

	$pr = get_project_data();
	$site_url2 = isset( $pr["domain_name"] )?$pr["domain_name"]:'';

	$ref = isset( $data["other_params"][ 'parent' ][0][ 'reference' ] ) ? $data["other_params"][ 'parent' ][0][ 'reference' ] : '';
	$ref_table = isset( $data["other_params"][ 'parent' ][0][ 'reference_table' ] ) ? $data["other_params"][ 'parent' ][0][ 'reference_table' ] : '';
	$ref_plugin = isset( $data["other_params"][ 'parent' ][0][ 'plugin' ] ) ? $data["other_params"][ 'parent' ][0][ 'plugin' ] : '';
	
	$child = array();
	if( isset( $data["other_params"][ 'children' ] ) && ! empty( $data["other_params"][ 'children' ] ) ){
		foreach( $data["other_params"][ 'children' ] as $ch ){
			$child[ $ch["reference"] ][] = $ch;
		}
	}
	
	$pp = isset( $pagepointer2 )?$pagepointer2:$pagepointer;
	
	if( defined("IPIN_APP_DEFAULT_AVATAR") && IPIN_APP_DEFAULT_AVATAR ){
		$pic1 = IPIN_APP_DEFAULT_AVATAR;
	}else{
		$pic1 = 'files/resource_library/images/avatars/1.jpg';
	}
	$pic = get_uploaded_files( $pp , $pic1, '', '', array( "return_link" => 1 ) );
	$pic = $pr["domain_name"] . $pic1;
	
	$print = 1;
	$hide_export = isset( $data["other_params"][ 'hide_export' ] ) ? $data["other_params"][ 'hide_export' ] : '';
	if( $hide_export ){
		$print = 0;
	}

	if( $print ){
		echo get_export_and_print_popup( ".table" , "#quick-print-container", "", 1 ) . "</div><br><br>"; 
	}

	switch( $table ){
	case 'workflow':
	default:
		echo '<div id="quick-print-container">';
		foreach( $data["other_params"][ 'parent' ] as $pval ){
		   ?>
	   		<div class="portlet"><?php
	   			$head = '';
	   			$h = '';
				switch( $table ){
				case 'comments':
					switch( $ref_table ){
					case 'ecm2':
					break;
					case 'workflow':
				        
						$h = '<div class="portlet-title line">
					         <div class="caption"><i class="icon-comments"></i></div>
					         <div class="tools">
					            <a href="" class="collapse"></a>
					            <a href="#portlet-config" data-toggle="modal" class="config"></a>
					            <a href="" class="reload custom-single-selected-record-button" override-selected-record="'. $ref .'" action="?action=comments&todo=view_details_by_reference&table='. $table .'&html_replacement_selector=latest-comments-container&modal=1"></a>
					            <a href="" class="remove"></a>
					         </div>
				      	</div>';
					case 'workflow':
					default:
						if( $ref ){
							$_act = '?module=&action='. $ref_table .'&todo=view_details';
							if( $ref_plugin ){
								$_act = '?module=&nwp_action='. $ref_table .'&nwp_todo=view_details&action='.$ref_plugin.'&todo=execute';
							}
							$head .= '<a href="#" class="btn btn-sm btn-default custom-single-selected-record-button" override-selected-record="'. $ref .'" action="'. $_act .'&html_replacement_selector='. $html_replacement_selector .'" title="View Details" target="_blank" new_tab="1">Preview &nearr;</a>';
							$head .= '<br /><br />';
						}
					break;
					}
				break;
				}
				echo $head;
				echo $h;
	   		?>
		      <div class="portlet-body" id="chats">
		         <div class="scroller" style="height: auto;" data-always-visible="1" data-rail-visible1="1">
		            <ul class="chats">
		            	<div style="display:block;">
						   <li class="in">
							<?php
								$user = get_name_of_referenced_record( array( "id" => $pval["created_by"], "table" => 'users', 'return_data' => 1 ) );
							?>
							  <img class="avatar img-responsive" alt="" src="<?php echo ( isset( $user[ 'photograph' ] ) && $user[ 'photograph' ] && $user[ 'photograph' ] !== 'none' && file_exists( $pp . $user[ 'photograph' ] ) ) ? ( get_uploaded_files( $pp , $user[ 'photograph' ], '', '', array( "return_link" => 1 ) ) ) : $pic; ?>" />
							  <div class="message">
								 <span class="arrow"></span>
								 <span class="name" style="font-weight:bold;"><?php if( isset( $user["name"] ) )echo $user["name"]; ?></span>
								 <span class="datetime" style="font-style:italic;">@ <?php echo date("d  M Y : h:ia" , $pval[ 'creation_date' ] ); ?></span>
								 <span class="body"><big>
									<?php echo __get_value( $pval[ 'message' ], 'message' ); ?>
								 </big></span>
							  </div>
						   </li>
							<div class="rply  hidden-print no-print" style="text-align:center;">
								<a href="#" class="btn blue custom-single-selected-record-button hidden-print no-print" override-selected-record="<?php echo $pval["id"]; ?>" action="?module=&action=comments&todo=reply_comment&html_replacement_selector=reply-comment-<?php echo $pval["id"]; ?>&callback=commentView.replyComment" ><i class="icon-reply"></i> Reply</a>
							</div>
						</div>
					   <li class="in" style="display:none;">
						<div id="reply-comment-<?php echo $pval["id"]; ?>">
						</div>
					   </li>
						<div class="replies replies-<?php echo $pval["id"]; ?>" id="<?php echo $pval["id"]; ?>">
							
							<?php
							if( isset( $child[ $pval["id"] ] ) && ! empty( $child[ $pval["id"] ] ) ){
								foreach( $child[ $pval["id"] ] as $value ){
									
									$user2 = get_name_of_referenced_record( array( "id" => $value[ 'created_by' ], "table" => 'users', "return_data" => 1 ) );
									
									?>
									<li class="out">
									  <img class="avatar img-responsive" alt="" src="<?php echo ( isset( $user2[ 'photograph' ] ) && $user2[ 'photograph' ] && $user2[ 'photograph' ] !== 'none' && file_exists( $pp . $user2[ 'photograph' ] ) ) ? ( get_uploaded_files( $pp , $user2[ 'photograph' ], '', '', array( "return_link" => 1 ) ) ) : $pic; ?>" />
									  <div class="message">
										 <span class="arrow"></span>
										 <a href="#" class="name"><?php if( isset( $user2["name"] ) )echo $user2["name"]; ?></a>
										 <span class="datetime">at <?php echo date("d  M Y : h:ia",$value[ 'creation_date' ]); ?></span>
										 <span class="body">
										 <?php echo __get_value( $value[ 'message' ], 'message' ); ?>
										 </span>
									  </div>
									</li>
									<?php
								}
							  ?>
							  <?php
						   } 
						   ?>
						</div>
						<li class="out b hidden-print no-print">
							<a href="#" id="view-more" class="hidden-print no-print" parent-id="<?php echo $pval["id"]; ?>" >View More</a>
						</li>
		            </ul>
		         </div>
				
		      </div>
	   		</div>
		  <?php
		}
		echo '</div>';
		?>

		<!-- <li class="out b hidden-print no-print replies-more-comment">
			<a href="#" id="view-more2" class="hidden-print no-print" parent-id="more-comment" >View More</a>
		</li> -->
		<?php
		/*echo '<li class="out b">
			<a href="#" class="custom-single-selected-record-button btn-block btn dark" action="?action=comments&todo=load_image_capture<?php echo $params; ?>" override-selected-record="<?php echo $ref; ?>">View More</a>
			<a href="#" id="view-more" class="" parent-id="<?php echo $pval["id"]; ?>" ></a>
		</li>';*/
	break;
	}
?>
<?php }else{
	?>
	<div class="note note-warning">No Comments Found</div>
	<?php
} ?>
</div>
<script type="text/javascript">
var commentView = {
	data: {},
	ref: '<?php echo $ref; ?>',
	ref_table: '<?php echo $ref_table; ?>',
	init: function(){
		$( 'div.replies' ).each(function(){
			commentView.data[ $( this ).attr( 'id' ) ] = $( this ).children( 'li.out' ).length;
		});

		$( document ).off( '#view-more,#view-more2' );
		$( document ).on( 'click', '#view-more,#view-more2', function(){
			var v = {
				id : $( this ).attr( 'parent-id' ),
				limit : commentView.data[ $( this ).attr( 'parent-id' ) ],
				callback : 'commentView.init',
			};

			var atp = 'view_more_comment';
			switch( $(this).attr( 'id' ) ){
			case 'view-more2':
				atp = 'view_more_comment2';
			break;
			}

			$.fn.cProcessForm.ajax_data = {
				ajax_data: v,
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?module=&action=comments&todo="+atp+'&reference='+commentView.ref+'&reference_table='+commentView.ref_table,
			};
			$.fn.cProcessForm.ajax_send();
		});
	},
	appendReply:function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.html_replacement ){
			$( 'div.replies-' + $.fn.cProcessForm.returned_ajax_data.id ).prepend( $.fn.cProcessForm.returned_ajax_data.html_replacement );
			$( 'div.replies' ).each(function(){
				commentView.data[ $( this ).attr( 'id' ) ] = $( this ).children( 'li.out' ).length;
				console.log( commentView.data );
			});
			if( $.fn.cProcessForm.returned_ajax_data.html_replacement_selector_one ){
				$( $.fn.cProcessForm.returned_ajax_data.html_replacement_selector_one ).parent().hide();
			}
		}
	},
	replyComment:function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.html_replacement_selector ){
			$( $.fn.cProcessForm.returned_ajax_data.html_replacement_selector ).parent().removeAttr( 'style' );
		}
	},
};
commentView.init();
</script>