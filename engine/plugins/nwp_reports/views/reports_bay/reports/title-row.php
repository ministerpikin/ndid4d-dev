<div class="row">
	<div class="col-12">
		<div class="page-title-boxX d-sm-flex align-items-center justify-content-between">
			<h5 class="mb-0 pb-1"><?php echo $rtit; ?> </h5>
			
			<div class="page-title-right d-sm-flex">
				<div class="dashboard-filter-buttons">
					<?php 
						if( !$spd ){
							if( ! ( isset( $hide_filter ) && $hide_filter ) ){ ?>
								<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=filter_card_all&report=<?php echo $rr; ?>&plugin=<?php echo $plugin; ?>&table=<?php echo $table; ?>&ckey=DashboardAllFilter" class="DashboardAllFilter custom-single-selected-record-button page-title-right"  override-selected-record="<?php echo '-' ?>" title="Filter entire Dashboard" style="marginX: 0 15px 0;">
									<i class="<?php echo empty( $addQuery ) ? 'ri-filter-line' : 'ri-filter-fill' ?> text-dark" style="font-size: 25px;"></i>
								</a>
							<?php } 							
						}
					?>
				</div>
			</div>
		</div>
		<br>
	</div>
</div>