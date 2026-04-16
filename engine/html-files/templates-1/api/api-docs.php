<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
// echo '<pre>'; print_r( $data ); echo '</pre>';
?>
<div class="row">
<div class="col-md-offset-3 col-md-6">
<!--
	<div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">Dashboards</li>
                            <li>
                                <a href="index.html" class="mm-active">
                                    <i class="metismenu-icon pe-7s-rocket"></i>
                                    Dashboard Example 1
                                </a>
                            </li>
                            <li class="app-sidebar__heading">UI Components</li>
                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-diamond"></i>
                                    Elements
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <li>
                                        <a href="elements-buttons-standard.html">
                                            <i class="metismenu-icon"></i>
                                            Buttons
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-dropdowns.html">
                                            <i class="metismenu-icon">
                                            </i>Dropdowns
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-icons.html">
                                            <i class="metismenu-icon">
                                            </i>Icons
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-badges-labels.html">
                                            <i class="metismenu-icon">
                                            </i>Badges
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-cards.html">
                                            <i class="metismenu-icon">
                                            </i>Cards
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-list-group.html">
                                            <i class="metismenu-icon">
                                            </i>List Groups
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-navigation.html">
                                            <i class="metismenu-icon">
                                            </i>Navigation Menus
                                        </a>
                                    </li>
                                    <li>
                                        <a href="elements-utilities.html">
                                            <i class="metismenu-icon">
                                            </i>Utilities
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>    
            <div class="app-main__outer">
                <div class="app-main__inner">
                </div>
            </div>
        </div>
    -->
<?php
if( isset( $data[ 'api' ] ) && is_array( $data[ 'api' ] ) && isset( $data[ 'api' ][ 'api_methods' ] ) && is_array( $data[ 'api' ][ 'api_methods' ] )  ){
	foreach ( $data[ 'api' ][ 'api_methods' ] as $key => $value) {
		if( isset( $value[ 'title' ] ) && $value[ 'title' ] ){
			echo '<h4><strong>'. $value[ 'title' ] . '</strong></h4>';
		}

		if( isset( $value[ 'request_type' ] ) && $value[ 'request_type' ] ){
			if( isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) && $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ){
				echo str_replace( '{name}', $value[ 'name' ], $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) . '<br><br>';

				// echo $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ];
			}
		}
			
		echo '
		<div class="note note-warning">
			<h4>Endpoints</h4>
			<p>'. $data[ 'default_settings' ][ 'endpoint' ] .'</p>
		</div>';

		if( isset( $value[ 'input' ] ) && ! empty( $value[ 'input' ] ) ){
			echo '<h5><strong>Request Parameters</strong></h5>';
			echo '<p>The Body of the request should contain the following fields</p>';

			$h = '
				<div class="card" >
				<div class="card-body">
					<table class="mb-0 table table-striped">
						<thead><th>Request</th><th>Description</th></thead>
						<tbody>';
			foreach( $value[ 'input' ] as $k => $v ){
				$h .= '<tr>';
					$h .= '<td class="col-md-4"><strong>'. $k . '</strong><br>';
					$h .= '<i>' . ( isset( $v[ 'type' ] ) ? $v[ 'type' ] : '' );
					if( isset( $v[ 'required' ] ) && $v[ 'required' ] ){
						$h .= '<br />required';
					}
					$h .= '</i></td>';
					switch( $k ){
					case 'fields':
						if( isset( $v[ 'tables' ] ) && ! empty( $v[ 'tables' ] ) ){
							$h .= '<td><pre>';
							$h .= 'tables:{';
								$margin = 7;
								$h .= '<div style="margin-left:'. $margin .'px;">';
								foreach(  $v[ 'tables' ] as $k1 => $v1 ){
									$margin += 7;
									$h .= '<div style="margin-left:'. $margin .'px;">';
									$h .= $k1 . ':{<br />';
									if( isset( $v1[ 'fields' ] ) && ! empty( $v1[ 'fields' ] ) ){
										$margin += 7;
										$h .= '<div style="margin-left:'. $margin .'px;">';
											$h .= 'fields:{<br />';
											$margin += 7;
											$h .= '<div style="margin-left:'. $margin .'px;">';
											foreach( $v1[ 'fields' ] as $k2 => $v2 ){
													$h .= $k2 . ': {}' . '<br />';
											}
											$h .= '</div>';
										$h .= '}</div>';
									}
									$h .= '}</div>';
								}
								$h .= '</div>';
							$h .= '}</pre>';
							$h .= '</td>';
						}
					break;
					default:
						$h .= '<td>'. $v[ 'description' ] .'</td>';
					break;
					}
				$h .= '</tr>';
			}
					$h .= '</tbody>
				</table>
			</div>
			</div>';

			echo $h;
		}
	}
}
?>
</div>
</div>
</div>