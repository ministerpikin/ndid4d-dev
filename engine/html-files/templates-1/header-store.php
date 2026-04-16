<!-- Navigation -->
<div class="container">
	<div class="masthead1">
		<?php
			$header_position = '';
			$field = 'header_position';
			if( isset( $data['store'][$field] ) )$header_position = $data['store'][$field];
			
			$header_options = '';
			$field = 'header_options';
			if( isset( $data['store'][$field] ) )$header_options = $data['store'][$field];
			
			$align = '';
			switch( $header_position ){
			case 'right':
				$align = ' style="text-align:right" ';
			break;
			case 'center':
				$align = ' style="text-align:center" ';
			break;
			}
		?>
        <h3 class="muted" <?php echo $align; ?>>
		<?php
			$title = '';
			$field = 'store_name';
			$dd = $data['pagepointer'];
            
            if( isset( $display_pagepointer ) && $display_pagepointer )
                $dd = $display_pagepointer;
                
			if( isset( $data['store'][$field] ) )$title = $data['store'][$field];
			
			$logo = $title;
			$field = 'logo';
			if( isset( $data['store'][$field] ) && file_exists( $data['pagepointer'].$data['store'][$field] ) )$logo = '<img src="'.$dd.$data['store'][$field].'" style="max-width:220px; max-height:90px;" title="'.$title.'" /><br />';
			
			switch( $header_options ){
			case 'show_logo_store_name':
				echo $logo;
				echo $title;
			break;
			case 'show_store_name':
				echo $title;
			break;
			case 'show_logo':
				echo $logo;
			break;
			default:
				echo $title;
			break;
			}
		?>
			
		  <small><?php
			$field = 'tagline';
			if( isset( $data['store'][$field] ) && $data['store'][$field] )echo $data['store'][$field];
		  ?></small>
		</h3>
        <div class="navbar">
			<!--
		  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse1">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  -->
          <div class="navbar-inner nav-collapse1 collapse1">
            
              <ul class="nav">
			  <li class="">
				<a href="#products-box-1">Products</a>
			  </li>
			  <li class="">
				<a href="#about-box">About Us</a>
			  </li>
			  <li>
                <a href="?page=hot_deals" class="dropdown-toggle" navigate-not="false" data-toggle="dropdown">Explore <span class="caret"> </span></a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a tabindex="-1" href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=hot_deals">Our Catalogue</a>
                    </li>
                    <li>
                        <a tabindex="-1" href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=stores">Stores</a>
                    </li>
                    <li>
                        <a tabindex="-1" href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=homepage">Zidoff.com</a>
                    </li>
                </ul>
			  </li>
              </ul>
            
          </div>
        </div><!-- /.navbar -->
      </div>
 </div>
<?php //include "dashboard-menu.php"; ?>