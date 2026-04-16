<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Date</th>
				  <th>Details</th>
				  <th>Category</th>
				  <th class="r">Staff</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$class = "";
					$e = get_employees();
					$cat = get_call_categories();
					
					$table = 'customer_call_log';
					
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