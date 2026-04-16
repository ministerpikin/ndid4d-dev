<h6><?php if( isset( $data['title'] ) && $data['title'] ) echo $data["title"]; ?> <small class="pull-right"><?php if( isset( $data['total'] ) && $data['total'] ) echo $data["total"]; ?></small></h6>
		<?php
			if( isset( $data['emails'] ) && $data['emails'] ){
				foreach( $data['emails'] as $key => $v ){
					?>
					<div class="alert alert-warning" style="background-color:#f7f7f7; color:#333;">
					  <button type="button" class="close" data-dismiss="alert"></button>
					  <h6><?php echo $key; ?></h6>
					  <div style="max-height:200px; overflow-y:auto;">
					  <table class="table table-striped table-bordered small-size">
					  <thead>
						<tr>
						<th>RECIPIENT</th>
						<th>SUBJECT</th>
						<th>TIME</th>
						</tr>
					  </thead>
					  <?php
						$count = 0;
						$success = 0;
						$fails = 0;
						foreach( $v as $kk => $vv ){
							if( ! $vv["recipient"] )continue;
							
							if( isset( $vv["status"] ) && $vv["status"] )
								++$success;
							else
								++$fails;
							
							++$count;
						?>
						 <tr class="info">
							<td><strong><?php echo $vv["recipient"]; ?></strong></td>
							<td><?php echo $vv["subject"]; ?></td>
							<!--<td><?php echo $vv["id"]; ?></td>-->
							<td><?php echo $vv["time"]; ?></td>
						 </tr>
						<?php
						}
					  ?>
					  </table>
					</div>
					  <small><strong>TOTAL { <?php echo $count; ?> }</strong> | SUCCESS { <?php echo $success; ?> } | FAILS { <?php echo $fails; ?> }</small>
					</div>
					<?php
				}
			}
		?>