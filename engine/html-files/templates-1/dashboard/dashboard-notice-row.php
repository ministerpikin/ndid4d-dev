<br />
<?php
    if( isset( $data['notice'] ) && ! empty( $data['notice'] ) ){
       ?>
        <div class="row">
       <?php
        foreach( $data['notice'] as $key => $val ){
        ?>
    <div class="col-lg-3 col-md-6">
		<div class="panel <?php echo $val['theme']; ?>">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
                        <i class="fa <?php echo $val['status_icon']; ?> fa-3x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="semi-huge">
                        <?php echo $val['status']; ?>
                        </div>
						<div><?php echo $val['title']; ?></div>
					</div>
				</div>
			</div>
			<a href="<?php echo $val['link']; ?>" title="<?php echo $val['link_title']; ?>">
				<div class="panel-footer">
					<span class="pull-left"><?php echo $val['link_caption']; ?></span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
        <?php          
        }
        ?>
        </div>
        <?php
    }
?>