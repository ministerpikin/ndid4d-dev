
<?php 
function __expenses( $data = array(), $type = "" ){
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover bordered">
			<thead>
			   <tr>
				  <th>Name</th>
				  <th>Position</th>
				  <th class="r">Salary</th>
			   </tr>
			</thead>
			<tbody>
			   <?php
				if( isset( $data ) && is_array( $data ) ){
					$class = "";
					$roles = get_access_roles();
					
					foreach( $data as $sval ){
						if( $sval["role"] == '1300130013' )continue;
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