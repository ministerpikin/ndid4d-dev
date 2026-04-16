<div class="container">

<div class="row-fluid">
	
	<div class="span9">
    <h1>Transaction Status</h1>
    <hr />
	<?php 
        $sidebar = array();
        if( isset( $data['sidebars'] ) ){
            $sidebar = $data['sidebars'];
        }
        
		//check for error
		if( isset( $data['typ'] ) && $data['typ'] ){
			switch( $data['typ'] ){
			case 'serror':
				echo $data['html'];
	?>
			<button class="btn btn-info">Consider Browsing Our Catalog</button>
			
	<?php
			break;
			}
		}else{
            
            ?>
            <div class="row-fluid">
            <div class="span12">
            <?php
            $key = 'order_details';
            if( isset( $data[ $key ] ) && $data[ $key ] ){
                ?>
                <div class="alert <?php $field = 'payment_status'; if( isset( $data[ $key ][ $field ] ) && $data[ $key ][ $field ] == 'paid' ){  echo 'alert-success';  }else{ echo 'alert-danger'; }  ?>">
                <h4>
                <?php
                $field = 'payment_status';
                if( isset( $data[ $key ][ $field ] ) && $data[ $key ][ $field ] ){
                    //get_payment_status
                    echo get_select_option_value( array( 'id' => $data[ $key ][ $field ], 'function_name' => 'get_payment_status' ) );
                }
                ?>
                </h4>
                </div>
                <?php
                
            }
            ?>
            </div>
            <!-- ./span4 -->
    
            </div>
            <!-- ./row -->
            
            <div class="row-fluid">
                    
                <div class="span12">
                    <div class="btn-group btn-group-horizontal">
                        <a href="?page=marketplace" class="btn btn-link"><i class="icon-share">&nbsp;</i> Continue Shopping</a>
                        <a href="?action=order&todo=site_order_manager" class="btn btn-link"><i class="icon-shopping-cart">&nbsp;</i> Ordered Items</a>
                        <a href="?action=order&todo=site_order_manager" class="btn btn-link"><i class="icon-share">&nbsp;</i> Payment History</a>
                    </div>
                </div>
                <!-- ./span12 -->
            </div>
            <!-- ./row -->
            
            <?php
                
                $title = 'Receipt';
                $data = $data[$key];
            ?>
            <?php include dirname( dirname( __FILE__ ) ) . "/globals/order-receipt-invoice-page.php"; ?>
		 
	</div>
	<!-- ./span9 -->
	<?php } ?>
    
    <div class="span3 well">
        <?php
            foreach( $sidebar as $side ){
                if( isset( $side['content_html'] ) )
                    echo $side['content_html'];
            }
        ?>
	</div>
	<!-- ./span3 -->
    
	</div>
	<!-- ./row -->
</div>
<!-- ./container -->