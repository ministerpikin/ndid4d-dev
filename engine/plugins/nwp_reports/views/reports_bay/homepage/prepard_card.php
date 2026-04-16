<?php
	
	$spd = isset($data['spd']) && $data['spd'] ? $data['spd'] : '';
	$xprt = 0;

	$access = get_accessed_functions();
	$super = !is_array( $access ) && $access == 1 ? 1 : 0;

	if( $super || (isset($access['accessible_functions'][ 'reports_bay.e_export.e_export' ]) && $access['accessible_functions'][ 'reports_bay.e_export.e_export' ] ) ){
		$xprt = 1;
	}	

	$__prepareCard = function( $opt = [], $rpd = [] ) use ($xprt, $spd) {
		$html = '';

		$rkey = '';
		$rname = '';
		$rtitle = '';
		$rtype = '';
		$col = 6;
		if( isset( $opt[ 'col' ] ) && $opt[ 'col' ] ){
			$col = $opt[ 'col' ];
		}

		if( isset( $opt[ 'id' ] ) && $opt[ 'id' ] ){
			if( isset( $rpd[ $opt[ 'id' ] ] ) && $rpd[ $opt[ 'id' ] ] ){
				$opt = $rpd[ $opt[ 'id' ] ];
			}

			$rkey = $opt[ 'id' ];
			$rtype = $opt[ 'type' ];
			$rname = $opt[ 'name' ];
			$rtitle = $opt[ 'title' ];
		}

		$class1 = '';
		$class2 = 'card-height-100';
		switch( $rtype ){
			case 'card':
				$class1 = ' pix ';
				$class2 = ' card-animate border card-border-info ';
				$cx = ' border card-border-info ';

				if( isset( $opt[ 'color' ] ) && $opt[ 'color' ] ){
					$cx = $opt[ 'color' ];
				}
				$class2 .= $cx;
			break;
		}

		$menus = [];
		if( !$spd ){
			$menus[] = '<a href="#" id="filter-card-'. $rkey .'" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=filter_card" class="custom-single-selected-record-button dropdown-item" override-selected-record="'. $rkey .'" title="Filter this Card">Filter</a>';

			$menus[] = '<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=modify_card" class="custom-single-selected-record-button dropdown-item" override-selected-record="'. $rkey .'" title="Modify this Report">Modify</a>';

			$menus[] = '<a href="#" class="ex-prt dropdown-item">Export As PDF</a>';
			$menus[] = '<a href="#" class="ex-img dropdown-item">Export As Image</a>';
			
			if( $xprt ){
				$menus[] = '<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=export_as_csv" id="export_csv-'. $rkey .'" class="custom-single-selected-record-button dropdown-item" override-selected-record="'. $rkey .'" title="Modify this Report">Export as CSV</a>';
			}			
		}
		

		$html .= '<div class="col-xl-'. $col .' col-md-'. $col .' '. $class1 .' " report-card="'. $rkey .'" >';

			$html .= '<textarea id="ep-'. $rkey .'" style="display: none;">'. json_encode( $opt ) .'</textarea>';
			$html .= '<div class="card '. $class2 .'">';

				switch( $rtype ){
					case 'card':
						$html .= '<div class="card-body">';
							if( !$spd ){
								$html .= '<div class="dropdown float-end">';
									
									$html .= '<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
										$html .= '<span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>';
									$html .= '</a>';
									$html .= '<div class="dropdown-menu dropdown-menu-end" style="">';

										$html .= implode( '', $menus );

									$html .= '</div>';

								$html .= '</div>';							
							}
							
							if( isset( $opt[ 'icon' ] ) && $opt[ 'icon' ] ){

								$html .= '<div class="d-flex justify-content-between">';
									$html .= '<div>';
										$html .= '<h5 class="fs-15 fw-semibold c-title '. ( isset( $opt[ 'color' ] ) ? 'text-white' : '' ) .'">'. $rname .'</h5>';
										$html .= '<p class="fw-mediumX c-sub-title '. ( isset( $opt[ 'color' ] ) ? 'text-white' : '' ) .' mb-0">'. $rtitle .'</p>';
									$html .= '</div>';
								$html .= '</div>';
								$html .= '<div class="d-flex align-items-end justify-content-between">';
									$html .= '<div>';
										$html .= '<h2 class="mt-4 ff-secondary fw-semibold '. ( isset( $opt[ 'color' ] ) ? 'text-white' : '' ) .' placeholder main-value">&nbsp;</h2>';
										$html .= '<p class="mb-0 text-mutedX '. ( isset( $opt[ 'color' ] ) ? 'text-white' : '' ) .' placeholder sub-value">&nbsp;</p> ';
									$html .= '</div>';
									$html .= '<div class="avatar-sm flex-shrink-0">';
										$html .= '<span class="avatar-title '. ( isset( $opt[ 'color' ] ) ? 'bg-soft-light' : 'bg-soft-primary' ) .' rounded fs-3 shadowX">';
											$html .= '<i class="mdi '. $opt[ 'icon' ] .' text-dark"></i>';
										$html .= '</span>';
									$html .= '</div>';
								$html .= '</div>';
							
							}else{

								$html .= '<div class="d-flex justify-content-between">';
									$html .= '<div>';
										$html .= '<h5 class="fs-15 fw-semibold c-title '. ( isset( $opt[ 'color' ] ) ? 'text-white' : '' ) .'">'. $rname .'</h5>';
										$html .= '<p class="fw-medium c-sub-title '. ( isset( $opt[ 'color' ] ) ? 'text-white' : 'text-muted' ) .' mb-0">'. $rtitle .'</p>';
										$html .= '<h2 class="mt-4 ff-secondary fw-semibold '. ( isset( $opt[ 'color' ] ) ? 'text-white' : 'text-dark' ) .' main-value placeholder">&nbsp;</h2>';
										$html .= '<p class="mb-0 '. ( isset( $opt[ 'color' ] ) ? 'text-white' : 'text-muted' ) .' sub-value placeholder">&nbsp;</p>';
									$html .= '</div>';
								$html .= '</div>';
								
							}

						$html .= '</div><!-- end card body -->';
					break;
					case 'nested_card':

						$html .= '<div class="card-body">';
							$html .= '<div class="card-header border-0 align-items-center d-flex">';
								$html .= '<h4 class="card-title mb-0 flex-grow-1">'. $rname .'</h4>';
								$html .= '<p class="fw-medium c-sub-title">'. $rtitle .'</p>';
								$html .= '<div>';
									$html .= '<div class="flex-shrink-0">';
										$html .= '<div class="dropdown card-header-dropdown">';
											$html .= '<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
												$html .= '<span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>';
											$html .= '</a>';
											$html .= '<div class="dropdown-menu dropdown-menu-end">';

												$html .= implode( '', $menus );

											$html .= '</div>';
										$html .= '</div>';
									$html .= '</div>';
								$html .= '</div>';
							$html .= '</div><!-- end card header -->';

							$html .= '<div class="card-header p-0 border-0 bg-soft-light" id="'. $rkey .'_charts" >';
								
							$html .= '</div><!-- end card header -->';
						$html .= '</div><!-- end card -->';
					break;
					case 'key_value':

						$html .= '<div class="card-body">';
							$html .= '<div class="card card-height-100">';
								$html .= '<div class="card-header align-items-center d-flex">';
									$html .= '<h4 class="card-title mb-0 flex-grow-1">'. $rname .'</h4>';
									$html .= '<div class="flex-shrink-0">';
										$html .= '<div class="dropdown card-header-dropdown">';
											$html .= '<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
												$html .= '<span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>';
											$html .= '</a>';
											$html .= '<div class="dropdown-menu dropdown-menu-end">';

												$html .= implode( '', $menus );

											$html .= '</div>';
										$html .= '</div>';
									$html .= '</div>';
								$html .= '</div><!-- end card header -->';
								$html .= '<div class="card-bodyX" id="'. $rkey .'_charts" >';
								$html .= '</div><!-- end cardbody -->';
							$html .= '</div><!-- end card -->';
						$html .= '</div><!-- end col -->';
					break;
					default:
						$html .= '<div class="card-header align-items-center d-flex">';
							$html .= '<h4 class="card-title mb-0 flex-grow-1 c-title">'. $rname .'</h4>';

							switch( $rtype ){
								case 'pline2':
								break;
								default:
									$html .= '<p class="fw-medium text-muted mb-0 c-sub-title">'. ( $rtitle ? $rtitle : '&nbsp' ) .'</p>';
								break;
							}

							if( !$spd ){
								$html .= '<div class="dropdown float-end">';
									
									$html .= '<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
										$html .= '<span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>';
									$html .= '</a>';
									$html .= '<div class="dropdown-menu dropdown-menu-end" style="">';
									
										$html .= implode( '', $menus );

									$html .= '</div>';
									
								$html .= '</div>';							
							}

						$html .= '</div>';

						$xc = 'p-0';
						switch( $rtype ){
							case 'pline':
							case 'pline2':
								$xc = '';
							break;
						}
						$html .= '<div class="card-body '. $xc .' placeholder">';

						switch( $rtype ){
							case 'plineX':
								$html .= '<div class="row " id="'. $rkey .'_charts" >';
								$html .= '</div>';
							break;
							default:
								$html .= '<div>';
									$html .= '<div class="apex-charts" dir="ltr" id="'. $rkey .'_charts" >';
									$html .= '</div>';
								$html .= '</div>';
							break;
						}
						
						$html .= '</div><!-- end card body -->';
					break;
				}
				
			$html .= '</div><!-- end card -->';

		$html .= '</div>';

		return $html;
	}
?>