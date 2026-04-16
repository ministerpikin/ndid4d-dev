<div class="pull-right">
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$s = 0;
	if( isset( $data[ 'departments' ] ) && $data[ 'departments' ] ){
		$option = '<select id="select-department">';
		$sel = "";
		$label = 'All Category';
		if( isset( $data[ 'departments_selected_option' ] ) && $data[ 'departments_selected_option' ] ){
			$sel = $data[ 'departments_selected_option' ];
		}
		if( isset( $data[ 'departments_all_label' ] ) && $data[ 'departments_all_label' ] ){
			$label = $data[ 'departments_all_label' ];
		}
		
		$option .= '<option value="all-departments">'.$label.'</option>';
		foreach( $data[ 'departments' ] as $k => $v ){
			$option .= '<option value="' . $k . '"';
			if( $sel == $k ){ $option .= ' selected="selected" '; }
			$option .= '>' . $v . '</option>';
		}
		$option .= '</select>';
		echo $option;
		$s = 1;
	}
	
	if( isset( $data[ 'months' ] ) && $data[ 'months' ] ){
		$option = '<select id="select-month">';
		$sel = "";
		$label = 'All Months';
		if( isset( $data[ 'months_selected_option' ] ) && $data[ 'months_selected_option' ] ){
			$sel = $data[ 'months_selected_option' ];
		}
		if( isset( $data[ 'months_all_label' ] ) && $data[ 'months_all_label' ] ){
			$label = $data[ 'months_all_label' ];
		}
		
		$option .= '<option value="all-months">'.$label.'</option>';
		foreach( $data[ 'months' ] as $k => $v ){
			$option .= '<option value="' . $k . '"';
			if( $sel == $k ){ $option .= ' selected="selected" '; }
			$option .= '>' . $v . '</option>';
		}
		$option .= '</select>';
		echo $option;
		$s = 1;
	}
	
	if( isset( $data[ 'years' ] ) && $data[ 'years' ] ){
		$option = '<select id="select-year">';
		$sel = "";
		if( isset( $data[ 'years_selected_option' ] ) && $data[ 'years_selected_option' ] ){
			$sel = $data[ 'years_selected_option' ];
		}
		
		
		if( isset( $data[ 'years_all_label' ] ) && $data[ 'years_all_label' ] ){
			$label = $data[ 'years_all_label' ];
			$option .= '<option value="all">'.$label.'</option>';
		}
		
		foreach( $data[ 'years' ] as $k => $v ){
			$option .= '<option value="' . $k . '"';
			if( $sel == $k ){ $option .= ' selected="selected" '; }
			$option .= '>' . $v . '</option>';
		}
		$option .= '</select>';
		echo $option;
		$s = 1;
	}
	
	if( isset( $data[ 'table' ] ) && $s ){
		?>
		<a href="#" class="btn dark btn-sm custom-action-button-old" function-id="1" function-class="<?php echo $data[ 'table' ]; ?>" function-name="reload_datatable" title="Filter Data" year="" month="" operator-id="-" department-id="-" id="select-change" style="display:none;">Go</a>
		<script type="text/javascript">
			$("select#select-year")
			.on("change", function(){
				$("a#select-change")
				.attr("year", $(this).val() )
				.attr("month", $("select#select-month").val() )
				.attr("department-id", $("select#select-department").val() )
				.click();
			});
			
			$("select#select-month")
			.add("select#select-department")
			.on("change", function(){
				$("select#select-year").change();
			});
		</script>
		<?php
	}

					// echo '<pre>'; print_r( $data[ 'table_filter' ] ); echo '</pre>';
	if( isset( $data[ 'table_filter' ][ 'form_action' ] ) && isset( $data[ 'table_filter' ][ 'fields' ] ) && ! empty( $data[ 'table_filter' ][ 'fields' ] ) ){
		$h = '<div style="display:flex;">';
		foreach( $data[ 'table_filter' ][ 'fields' ] as $f ){
			$action = '';
			$f["minlength"] = isset( $f["minlength"] ) ? $f["minlength"] : '0';
			$f["tags"] = isset( $f["tags"] ) ? $f["tags"] : '';
			$f["data-params"] = isset( $f["data-params"] ) ? $f["data-params"] : '';
			$f["value"] = isset( $f["value"] ) ? $f["value"] : '';
			$f["name"] = isset( $f["name"] ) ? $f["name"] : '';

			switch( $f[ 'type' ] ){
			case 'select2':
				if( isset( $f[ 'action' ] ) && $f[ 'action' ] && isset( $f[ 'todo' ] ) && $f[ 'todo' ] ){

					if( ! ( isset( $f[ 'class' ] ) && $f[ 'class' ] ) )$f[ 'class' ] = '';
					
					$f[ 'class' ] .= ' select2 ';
					$action = '?action=' . $f[ 'action' ]. '&todo=' . $f[ 'todo' ].( isset( $f[ 'params' ] ) && $f[ 'params' ] ? $f[ 'params' ] : '' );

					$x = array_chunk( preg_split('(&|=)', $action ), 2 );
					$xx = array_combine( array_column($x, 0), array_column($x, 1) );

					if( isset( $xx[ $f[ 'name' ] ] ) && $xx[ $f[ 'name' ] ] && ! $f[ 'value' ] ){
						$f[ 'value' ] = $xx[ $f[ 'name' ] ];
					}

				}

				$h .= '&nbsp;&nbsp;<input class="form-controlx '. ( isset( $f["class"] )?$f["class"]:'' ) .'" type="text" name="'. $f["name"] .'" value="'. $f["value"] .'" placeholder="'. ( isset( $f["label"] )?$f["label"]:'' ) .'" label="'. ( isset( $f["label"] )?$f["label"]:'' ) .'" action="'. $action .'" data-params="'. $f["data-params"] .'" minlength="'. $f["minlength"] .'" tags="'. $f["tags"] .'" />';
			break;
			case 'select':
				if( isset( $f[ 'options' ] ) && $f[ 'options' ] ){
					$sel = isset( $f[ 'selected' ] ) ? $f[ 'selected' ] : '';
					
					if( is_array( $f[ 'options' ] ) ){
						$fx = $f[ 'options' ];
					}else if( function_exists( $f[ 'options' ] ) ){
						$fx = $f[ 'options' ]();
					}else{
						$fx = array();
					}
					$opt = '';
					if( ! empty( $fx ) ){
						if( isset($f['add_empty']) && $f['add_empty'] ){
							$opt .= '<option value=""> </option>';
						}
						foreach( $fx as $fk => $fv ){
							$opt .= '<option value="'. $fk .'" '. ( $fk == $sel ? 'selected' : '' ) .'>'. $fv .'</option>';
						}
					}
					
					$h .= '&nbsp;&nbsp;<select class="form-controlx '. ( isset( $f["class"] )?$f["class"]:'' ) .'" type="text" name="'. $f["name"] .'" placeholder="'. ( isset( $f["label"] )?$f["label"]:'' ) .'" action="'. $action .'" data-params="'. $f["data-params"] .'" minlength="'. $f["minlength"] .'" tags="'. $f["tags"] .'" >'. $opt .'</select>';
				}
			break;
			}

		}
		$h .= '</div>';

		?>
		<form action="<?php echo $data[ 'table_filter' ][ 'form_action' ]; ?>" method="post" class="activate-ajax" id="datatable-filter">
		<?php echo $h; ?>
		</form>
		<script type="text/javascript">
			$("form#datatable-filter")
			.find("select,input")
			.on("change", function(){
				$("form#datatable-filter").submit();
			});
		</script>
		<?php
	}
?>
</div>
</div>