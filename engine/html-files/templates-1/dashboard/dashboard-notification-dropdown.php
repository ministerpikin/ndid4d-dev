<?php
	if( isset( $data['notifications'] ) && is_array( $data['notifications'] ) && ! empty( $data['notifications'] ) ){
		$now = date("U");
		$count = 0;
		foreach( $data['notifications'] as $key => $sval ){
			?>
			<li>  
		    <a href="#">
			<?php if( $sval["status"] == "unread" ){ ?>
			<span class="time"><?php echo time_passed_since_action_occurred( $now - doubleval( $sval["creation_date"] ), 2 ); ?></span>
			<strong>
			<?php echo $sval["title"]; ?>
			</strong>
			<?php 
			}else{
				?>
				<span class="time"><?php echo time_passed_since_action_occurred( $now - doubleval( $sval["creation_date"] ), 2 ); ?></span>
				<?php 
					echo $sval["title"];
			} 
			?>
			</a>
			</li>
			<?php
			++$count;
			if( $count > 2 )break;
		}
		
	}
 ?>