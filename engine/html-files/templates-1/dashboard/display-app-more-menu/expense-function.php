
<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th><?php if( $type == "year" ){ ?>Category<?php }else{ ?>Description {Vendor}<?php } ?></th>
				  <th class="r">Amount Due</th>
				  <th class="r">Amount Owed</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$categories = get_types_of_expenditure();
					$vendors = get_vendors();
					$class = "";
					
					$date_filter = "d-M-Y";
					if( $type == "year" )$date_filter = "F, Y";
						
					foreach( $data as $sval ){
						include "expense-list.php";
					}
				}
			?>
			</tbody>
			</table>
		</div>
		
	</div>
	<?php
}
?>