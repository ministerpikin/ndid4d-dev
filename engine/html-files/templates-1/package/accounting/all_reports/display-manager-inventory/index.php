<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	//$super = 1;
	//print_r( $access );
	
	$active = 'active';
	
	$tabs = frontend_tabs();
	
	$no_of_visible_tabs = 0;
	if( function_exists("get_hospital_number_of_visible_tabs_settings") ){
		$no_of_visible_tabs = get_hospital_number_of_visible_tabs_settings();
	}
	
	if( ! $no_of_visible_tabs ){
		$no_of_visible_tabs = 7;
	}
	
	$visible_tabs_count = 0;
?>
	<div class="tabbable-custom " id="main-tabs">
		<ul class="nav nav-tabs ">
		   <?php 
			$more_tabs = '';
			foreach( $tabs as $tk => $tval ){
				
				if( ! $super ){
					$yes_access_role = 0;
					if( isset( $tval["access_role"] ) && isset( $access[ $tval["access_role"] ] ) ){
						$yes_access_role = 1;
					}
					
					if( ! $yes_access_role )continue;
				}
				
				
				if( $visible_tabs_count > $no_of_visible_tabs ){
					$more_tabs .= '<li ><a href="#'. $tk .'" id="'. $tk .'" class="custom-single-selected-record-button empty-tab more-tab" override-selected-record="1" action="'. $tval["action"] .'&is_menu=1" data-toggle="tab">'. $tval["title"] .'</a></li>';
				}else{
				?>
				<li class="<?php echo $active; ?>"><a href="#<?php echo $tk; ?>" id="<?php echo $tk; ?>" class="custom-single-selected-record-button empty-tab" override-selected-record="1" action="<?php echo $tval["action"]; //&html_replacement_selector=search-for-patient ?>" data-toggle="tab"><?php echo $tval["title"]; ?></a></li>
				<?php
				}
				
				$active = '';
				
				++$visible_tabs_count;
			}
			
			if( $more_tabs ){
			?>
			<li class="dropdown" id="more-tab-handle">
			   <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <i class="icon-angle-down"></i></a>
			   <ul class="dropdown-menu" role="menu">
				  <?php echo $more_tabs; ?>
			   </ul>
			</li>
			<?php
			}
		   ?>
		   
		</ul>
		<div class="tab-content" style="background:#f1f1f1; /*#ebebeb;*/">
		   <div class="tab-pane active" id="dash-board-main-content-area">
				<div style="text-align:center;">
					<br />
					<br />
					<br />
					<img src="hospital-assets/img/ajax-loading.gif" />
					<br />
					<br />
					<br />
				</div>
		   </div>
		   
		   <div class="tab-pane" id="loading-tab">
				<div style="text-align:center;">
					<br />
					<br />
					<br />
					<img src="hospital-assets/img/ajax-loading.gif" />
					<br />
					<br />
					<br />
				</div>
		   </div>
		   
		</div>
	 </div>
	 
	 
</div>