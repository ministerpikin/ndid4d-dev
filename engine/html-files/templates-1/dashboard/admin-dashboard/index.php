<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<div class="row" style="margin:10px;"> 
		<div class="col-md-2"> 
		
		<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="dashboard-stat blue">
                  <div class="visual">
                     <i class="icon-comments"></i>
                  </div>
                  <div class="details">
                     <div class="number"  style="font-size:25px;">
                        <?php $key = "amount_deposited"; if( isset( $data['stats'][ $key ] ) )echo number_format($data['stats'][ $key ], 0); ?>
                     </div>
                     <div class="desc"><?php echo date("F"); ?></div>
                  </div>
                  <a class="more" href="#">
                  Amount Deposited
                  </a>                 
               </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="dashboard-stat green">
                  <div class="visual">
                     <i class="icon-globe"></i>
                  </div>
                  <div class="details">
                     <div class="number"  style="font-size:25px;"><?php $key = "amount_withdrawn"; if( isset( $data['stats'][ $key ] ) )echo number_format($data['stats'][ $key ], 0); ?></div>
                     <div class="desc"><?php echo date("F"); ?></div>
                  </div>
                  <a class="more" href="#">
                  Amount Withdrawn
                  </a>                 
               </div>
            </div>
			 <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="dashboard-stat red">
                  <div class="visual">
                     <i class="icon-bell"></i>
                  </div>
                  <div class="details">
                     <div class="number" style="font-size:25px;"><?php $key = "balance"; if( isset( $data['stats'][ $key ] ) )echo number_format($data['stats'][ $key ], 0); ?></div>
                     <div class="desc"><?php echo date("F"); ?></div>
                  </div>
                  <a class="more" href="#">
                  Balance
                  </a>                 
               </div>
            </div>
         </div>
		
		</div>
		
		<div class="col-md-3">
			<div id="chart-container">
				
			</div>
		</div>
		
		<div class="col-md-7">
			<div id="chart-container-1" class="resizable-height auto-scroll">
				
			</div>
		</div>
		
		</div>
</div>