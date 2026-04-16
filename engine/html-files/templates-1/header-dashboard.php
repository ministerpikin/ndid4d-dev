<!-- Navigation -->
<style type="text/css">
.spin-color .spin-color-1 > li > a{
    color:#b94a48;
}
.spin-color .spin-color-2 a{
    color:#777;
}
.spin-color .spin-color-1 a strong{
    color:#b94a48;
}
.top-bar-color{
    color:#b94a48;
}

.goog-te-gadget-simple{
    padding: 8px 6px;
    border: 1px solid #ddd !important;
    border-radius: 6px;
}
.btn-group > .dropdown-menu.pull-right:before {
position: absolute;
top: -7px;
right: 9px;
display: inline-block;
border-right: 7px solid transparent;
border-bottom: 7px solid #ccc;
border-left: 7px solid transparent;
border-bottom-color: rgba(0, 0, 0, 0.2);
content: '';
}
.btn-group > .dropdown-menu.pull-right:after {
position: absolute;
top: -6px;
right: 10px;
display: inline-block;
border-right: 6px solid transparent;
border-bottom: 6px solid #ffffff;
border-left: 6px solid transparent;
content: '';
}
.btn-group > .dropdown-menu.pull-left:before, .navbar .nav > li > .dropdown-menu:before {
position: absolute;
top: -7px;
left: 9px;
display: inline-block;
border-right: 7px solid transparent;
border-bottom: 7px solid #ccc;
border-left: 7px solid transparent;
border-bottom-color: rgba(0, 0, 0, 0.2);
content: '';
}
.btn-group > .dropdown-menu.pull-left:after, .navbar .nav > li > .dropdown-menu:after {
position: absolute;
top: -6px;
left: 10px;
display: inline-block;
border-right: 6px solid transparent;
border-bottom: 6px solid #ffffff;
border-left: 6px solid transparent;
content: '';
}
</style>
<nav class="spin-color navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    
	<div class="navbar-header" style="width:18.5%;">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="?page=homepage" title="Zidoff" navigate-not="true" style="width:100%;">
			<img src="<?php echo $data['pagepointer']; ?>images/logo.png" title="Zidoff" alt="Zidoff" alt="Zidoff" title="Zidoff" style="width:100%; max-width:170px;"/>
			<div style="font-size:0.76em; color:#115798;"><i><?php echo GLOBALS_DEFAULT_SLOGAN; ?></i></div>
		</a>
	</div>
	<!-- /.navbar-header -->
    
    <?php include "dashboard-menu.php"; ?>
    
    <div class="pull-left" style="width:81.5%;">
    
    <div class="row-fluid">
    
	<ul class="nav navbar-top-links navbar-left spin-color-1">
        
		<li class="dropdown" style="background:#eee;">
			<a href="#" class="dropdown-toggle" navigate-not="false" data-toggle="dropdown" style="background-color: #a9dba9; text-shadow:none; color:#333; border-color: #46a546; -webkit-border-radius: 0 0 4px 4px; -moz-border-radius:0 0 4px 4px; border-radius: 0 0 4px 4px;">Our Catalogue <i class="fa fa-caret-down"></i></a>
			<ul class="dropdown-menu">
                <?php
			if( isset( $data[ 'categories' ] ) && is_array( $data[ 'categories' ] ) && isset( $data[ 'terminating_categories' ] ) && is_array( $data[ 'terminating_categories' ] ) ){
				
				$catgeories = $data[ 'terminating_categories' ];
                $category_options = '';
                
                $transform_cat = array();
                $selected_category = '';
                
				foreach( $data[ 'categories' ] as $key => $val ){
					if( isset( $catgeories[ $val['category'] ][ 'parents' ][1] ) && $catgeories[ $val['category'] ][ 'parents' ][1] ){
                        
                        if( isset( $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] ) ){
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] += $val['count'];
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['record_id'] .= ':::'.$val['category'];
                        }else{
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['key'] = $catgeories[ $val['category'] ][ 'parents' ][1]['id'];
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['title'] = $catgeories[ $val['category'] ][ 'parents' ][1]['title'];
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] = $val['count'];
                            $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['record_id'] = $val['category'];
                            
                            
                            $selected_category .= $catgeories[ $val['category'] ][ 'parents' ][1]['id'].':::' . $val['category'];
                        }
                    }
                }

                ksort($transform_cat);
                
                    foreach( $transform_cat as $key => $val ){
                    
                    $_SESSION['category_nav'][ $val['key'] ] = $val['key'].':::'.$val['record_id'];
                    
                    $category_options .= '<option value="'.$val['key'].'">'.$val['title'].'</option>';
                    //$category_options .= '<option value="'.$val['key'].'">'.$val['title'].' ('.round($val['count'], 0).')</option>';
                ?>			
                            <li <?php if( isset($_GET['record_id']) && $val['key'] == $_GET['record_id'] ){ ?>class="active"<?php } ?>>
                                <a tabindex="-1" href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>category/<?php echo clean1( $val['title'] ); ?>" data-href="?page=product-category&record_id=<?php echo $val['key']; ?>">
                                <?php echo $val['title']; ?>
                                 <!--(<?php echo round($val['count'], 0); ?>)-->
                                </a>
                            </li>
                <?php
                    }
                 
			}
            ?>
			</ul>
			
			<!-- /.dropdown-messages -->
		</li>
        
		<!-- /.dropdown -->
        
        <?php
			//print_r( $data['menu'] );
			$first = true;
			$key = 'top_menu_bar_left';
			if( isset( $data['menu'][ $key ] ) && $data['menu'][ $key ] ){
				foreach( $data['menu'][ $key ] as $k ){
					if( $k['menu_type'] == 'seperator' || $k['menu_type'] == 'header' ){
					?>
					<li class="pull-right disabled"><a href="#"><?php echo $k['title_text']; ?></a></li>
					<?php
					}else{
						if( $first ){
							$first = false;
					?>
					<li <?php //if( isset($_GET['page']) && '?page='.$_GET['page'] == $k['url'] ){ ?>class="active"<?php //} ?>><a href="<?php echo $k['url']; ?>" navigate-not="false" title="<?php echo $k['tooltip']; ?>"><strong class="text-error"><?php echo $k['title_text']; ?></strong></a></li>
					<?php
						}else{
					?>
					<li <?php //if( isset($_GET['page']) && '?page='.$_GET['page'] == $k['url'] ){ ?>class="active"<?php //} ?>><a href="<?php echo $k['url']; ?>" navigate-not="false" title="<?php echo $k['tooltip']; ?>"><?php echo $k['title_text']; ?></a></li>
					<?php
						}
					}
				}
			}
		  ?>
          
	</ul>
	<!-- /.navbar-top-links -->

	<ul class="nav navbar-top-links navbar-right spin-color-2">
        <?php
          
            //check if user is logged in
            $user = md5( 'ucert' . $_SESSION['key'] );
            // Get current user details session settings
            if( isset( $_SESSION[ $user ]['id'] ) && $_SESSION[ $user ]['id'] ) {
            ?>
            <li class="pull-right1"><a href="?action=welcome_new_user&todo=user_dashboard&page=user-dashboard" navigate-not="false" title="<?php echo $_SESSION[ $user ]['email']; ?>"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION[ $user ]['email']; ?></a></li>
            <li class="pull-right1 disabled"><a href="#">|</a></li>
            <li class="pull-right1"><a href="?page=sign-out" class="" navigate-not="false" title="<?php echo GLOBALS_LOGOUT_TEXT; ?>"><i class="fa fa-power-off fa-fw"></i> <?php echo GLOBALS_LOGOUT_TEXT; ?></a></li>
            <?php
            }else{
                //print_r( $data['menu'] );
                $key = 'top_menu_bar_right';
                if( isset( $data['menu'][ $key ] ) && $data['menu'][ $key ] ){
                    
                    foreach( $data['menu'][ $key ] as $k ){
                        if( $k['menu_type'] == 'seperator' || $k['menu_type'] == 'header' ){
            ?>
            <li class="pull-right1 disabled"><a href="<?php echo $k['url']; ?>" navigate-not="false" title="<?php echo $k['tooltip']; ?>"><?php echo $k['title_text']; ?></a></li>
            <?php
                        }else{
            ?>
            <li class="pull-right1"><a href="<?php echo $k['url']; ?>" navigate-not="false" title="<?php echo $k['tooltip']; ?>"><?php echo $k['title_text']; ?></a></li>
            <?php			
                        }
                    }
                }
            
            }
		?>
	</ul>
	<!-- /.navbar-top-links -->
    
    </div>
    <!-- /.row-fluid -->
    
    <div class="row-fluid">
        <hr />
    </div>
    <div class="row-fluid">
        <div class="span7">
        
        <form id="product-search-form" action="" method="get" class="responsive-split-11">
			<div class="input-append input-group" style="width:100%;">
				<input type="text" placeholder="<?php echo GLOBALS_FIND_ITEM_PRODUCT_TEXT; ?>" id="appendedInputButton" class="form-control responsive-element-21" style="border-radius:0; box-shadow:none; border:1px solid #ddd; border-right:none; height:38px; width:50%;" name="s" value="<?php if( isset($_GET['s']) && $_GET['s'] )echo $_GET['s']; ?>" />
				<select class="form-control hide-in-mobile chosen-select" style="border-radius:0px; box-shadow:none; border:1px solid #ddd; -moz-border-radius:0px; -webkit-border-radius:0px; width:50%;" name="categories">
                    <option value="all"><?php echo GLOBALS_ALL_CATEGORIES_TEXT; ?></option>
                    <?php
                        if( isset( $category_options ) )echo $category_options;
                    ?>
				</select>
				<input type="hidden" value="hot_deals" name="page" />
				<input type="hidden" value="list" name="data_view_field" />
				<input type="hidden" value="both" name="data_sort_field" />
                <span class="input-group-btn">
				<input type="submit" class="btn btn-primary responsive-element-3" value="Search" style="padding-bottom:10px; min-width:85px;" />
                </span>
			</div>
        </form>
        
        </div>
        <div class="span5" style="text-align:right;">
        
            <?php
                $flag = '';
                if( file_exists( $data['pagepointer'] . 'images/country_flag/'.SELECTED_COUNTRY_FLAG.'.png' ) ){
                    $flag = $data['pagepointer'] . 'images/country_flag/'.SELECTED_COUNTRY_FLAG.'.png';
                }else{
                    if( file_exists( $data['pagepointer'] . 'images/country_flag/'.SELECTED_COUNTRY_FLAG.'.jpg' ) ){
                        $flag = $data['pagepointer'] . 'images/country_flag/'.SELECTED_COUNTRY_FLAG.'.jpg';
                    }
                } 
            ?>
			<div class="btn-group" style="display:inline-block; margin-top: -1px; margin-left: -8px;" id="country-select-box" ajax-request="true" data-action="cart" data-todo="get_shopping_cart_widget" data-record-id="">
			  <a class="btn" data-toggle="dropdown" id="country-select-popover" href="#" style=" -webkit-border-radius: 4px 0 0 4px; -moz-border-radius:4px 0 0 4px; border-radius:4px 0 0 4px; border:1px solid #ddd; color:#777;  padding-top: 8px; border: 1px solid #eee; border-right: none; box-shadow: none; color:#111;" >
				<img src="<?php echo $flag; ?>" id="country-select-flag" data-pointer="<?php echo $data['pagepointer'] . 'images/country_flag/updating.gif'; ?>" width="23" /> 
				<?php echo SELECTED_COUNTRY_COUNTRY; ?> 
				(<?php if( SELECTED_COUNTRY_ISO_CODE == 'GLOBAL' )echo 'US '; echo SELECTED_COUNTRY_CURRENCY; ?>) 
				| <?php echo get_select_option_value( array( 'id' => SELECTED_COUNTRY_LANGUAGE, 'function_name' => 'get_languages' ) ); ?>
                &nbsp;<span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu pull-right" style="margin-top:10px;" id="country-popover-container">
				<li>
					<div style="margin:10px; 1width:300px; text-align:left;">
                        <form method="get" action="" id="country-select-form">
                            <h4>Regional Settings</h4>
                            <hr />
                            <label>Location</label>
                            <select id="country-select-box" class="form-gen-element" style="/*text-indent:25px;*/ border-color:#eee; background-image:url(<?php echo $flag; ?>); background-position:5px center; background-repeat:no-repeat;" data-pointer="<?php echo $data['pagepointer'] . 'images/country_flag/updating.gif'; ?>" ajax-request="true" data-action="country_list" data-todo="change_country" data-record-id="">
                                <?php
                                    $d = get_default_country_details();
                                    if( SELECTED_COUNTRY_ID == '1' ){
                                        echo '<option selected="selected" value="'.SELECTED_COUNTRY_ID.'">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;'.SELECTED_COUNTRY_COUNTRY.'</option>';
                                    }else{
                                        echo '<option value="'.$d['id'].'">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;'.$d['country'].'</option>';
                                    }
                                    
                                    if( isset( $data['countries'] ) && is_array( $data['countries'] ) ){
                                        foreach( $data['countries'] as $key => $val ){
                                           if( $val["display_on_site"] == 'yes' ){
                                               if( SELECTED_COUNTRY_ID == $key )echo '<option selected="selected" value="'.$key.'">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;'.$val["country"].'</option>';
                                               else echo '<option value="'.$key.'">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;'.$val["country"].'</option>';
                                           }
                                        }
                                    }
                                ?>
                            </select>
                            <br />
                            <br />
                            <label>Language</label>
                            <!--
                            <select id="country-select-box" class="form-gen-element" style="/*text-indent:25px;*/ border-color:#eee; background-position:5px center; background-repeat:no-repeat;">
                                <?php
                                    /*$languages = get_languages();
                                    if( isset( $languages ) && is_array( $languages ) ){
                                        foreach( $languages as $key => $val ){
                                           if( SELECTED_COUNTRY_ID == $key )echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
                                           else echo '<option value="'.$key.'">'.$val.'</option>';
                                        }
                                    }*/
                                ?>
                            </select>
                            -->
                            <div id="google_translate_element"></div><script type="text/javascript">
                            function googleTranslateElementInit() {
                              new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                            }
                            </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
                            <br />
                            <br />
                            <!--
                            <input type="submit" class="btn btn-primary pull-right" value="Update" />
                            <br />
                            <br />
                            -->
                        </form>
					</div>
				</li>
			  </ul>
			</div>
			
			<div class="btn-group" style="margin-top: -1px; margin-left: -8px; margin-right: 15px; min-width: 90px; width: 20%;" id="shopping-cart-dropdown-box" ajax-request="true" data-action="cart" data-todo="get_shopping_cart_widget" data-record-id="">
			  <a class="btn active dropdown-toggle" data-toggle="dropdown" href="#" style="padding-top: 9px; border: 1px solid #eee; border-left: none; box-shadow: none; border-radius: 0 4px 4px 0; background-color: #a9dba9; color:#111;">
                <i class="fa fa-shopping-cart"></i>
				Cart <span id="shopping-cart-dropdown-box-count"></span>&nbsp;
				<i class="fa fa-caret-down"></i>
			  </a>
			  <ul class="dropdown-menu pull-right" style="margin-top:10px;">
				<li>
					<div id="shopping-cart-dropdown-box-container" style="margin:10px; width:300px; text-align:left;">
                    
					</div>
				</li>
			  </ul>
			</div>
			
		</div>
    </div>
    <!-- /.row-fluid -->
    
    </div>
    <!-- /.pull-left -->
    
</nav>