<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

	<div class="row">
		  <div class="col-xs-12">
			<div class="shopping-cart-table">
			<table class="table table-striped table-bordered table-hover">
			 <thead>
				<tr>
				   <th>Date</th>
				   <th>Action</th>
				   <th>User</th>
				   <th>Comment</th>
				</tr>
			 </thead>
			  <tbody>
				<?php
					$htrail = '';
					if( isset( $e_data["status_updates"] ) && is_array( $e_data["status_updates"] ) && ! empty( $e_data["status_updates"] ) ){
						krsort( $e_data["status_updates"] );
						
						foreach( $e_data["status_updates"] as $v2 ){
							$htrail .= '<tr>';
							$htrail .= '<td>'. date("d-M-Y H:i", doubleval( $v2["date"] ) ) .'</td>';
							$htrail .= '<td>'. ( isset( $v2["action"] )?$v2["action"]:$v2["status"] ) .'</td>';
							$htrail .= '<td>'. get_name_of_referenced_record( array( "id" => $v2["user"], "table" => "users" ) ) .'</td>';
							$htrail .= '<td>'. $v2["msg"] .'</td>';
							$htrail .= '</tr>';
						}
						
						echo $htrail;
					}
				?>
			  </tbody>
			 </table>
		  </div>
		  </div>
	</div>
	  
</div>