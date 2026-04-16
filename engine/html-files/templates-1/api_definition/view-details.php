<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
    .section{
        background: white;
        padding: 30px;
    }
    .docs > a{
        padding-left: 20px !important;
    }
    .documentation {
        position: fixed;
        width: 18%;
    }
    .documentation .vertical-nav-menu ul>li>a {
        color: #4a4a4a;
    }
    .documentation .app-sidebar__heading:hover{
        color:#2f9859;
    }
    .documentation .sub-menu{
        text-overflow: ellipsis;
        overflow-x: hidden;
    }
</style>
<?php
?>
<div class="row" id="api-docs">
<div class="col-md-12">
<?php
$hash_values = array( 'api_hash_key' );

//echo '<pre>'; print_r( $data[ 'items' ] ); echo '</pre>';
$test_form = array();
$h = '';
$pr = get_project_data();

if( isset( $data[ 'items' ] ) && is_array( $data[ 'items' ] ) ){
	$tables = array();
	
	$html = '<div >';
	foreach ( $data[ 'items' ] as $key => $value) {

		$test_form["title"] = $value[ 'title' ];
		$html .= '<h3><strong>'. $value[ 'title' ] . '</strong></h3><p><strong>API METHOD:</strong> '. $value[ 'api_method' ] .'</p>';

		if( isset( $value[ 'request_type' ] ) && $value[ 'request_type' ] ){
			if( isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) && $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ){
				$html .= str_replace( '{name}', $value[ 'name' ], $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) . '<br><br>';

				// $html .= $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ];
			}
			
			$test_form["endpoint"] = $data[ 'default_settings' ][ 'endpoint' ];
			$html .= '
			<div class="note note-warning">
				<h4>Endpoints</h4>
				<p>'. $data[ 'default_settings' ][ 'endpoint' ] .'</p>
			</div>';

			switch( $value[ 'request_type' ] ){
			case "widget":
				$hash_values = array();
				$h .= '<p>Copy the code snipet and paste it into your project</p>';
				$h .= '<div class="tabbable tabbable-custom">';
					$h .= '<ul class="nav nav-tabs">';
					   $h .= '<li class="active"><a data-toggle="tab" href="#tab-iframe">HTML IFRAME</a></li>';
					   $h .= '<li><a data-toggle="tab" href="#tab-js">JavaScript</a></li>';
					   $h .= '<li><a data-toggle="tab" href="#tab-button">HTML BUTTON</a></li>';
					$h .= '</ul>';
					$h .= '<div class="tab-content">';
						$h .= '<div class="tab-pane" id="tab-js">';
							$h .= '<textarea rows="5" class="form-control" disabled><a class="nw-widget-new_ticket" data-width="340" data-height="340" href="'.$test_form["endpoint"].'?api_method='.$value[ 'api_method' ].'&api_key=YOUR_API_KEY">New Ticket</a><script async src="'.$pr["domain_name"].'widgets/tickets/new-ticket.js" charset="utf-8"></script></textarea>';
						$h .= '</div>';
						$h .= '<div class="tab-pane active" id="tab-iframe">';
							$h .= '<textarea rows="5" class="form-control" disabled><iframe src="'.$test_form["endpoint"].'?api_method='.$value[ 'api_method' ].'&api_key=YOUR_API_KEY" border="0" width="100%" height="450px;" /></textarea>';
						$h .= '</div>';
						$h .= '<div class="tab-pane" id="tab-button">';
							$h .= '<textarea rows="5" class="form-control" disabled><a target="_blank" href="'.$test_form["endpoint"].'?api_method='.$value[ 'api_method' ].'&api_key=YOUR_API_KEY">New Ticket</a></textarea>';
						$h .= '</div>';
					$h .= '</div>';
				$h .= '</div>';
			break;
			}
		}
		
		if( isset( $value[ 'input' ] ) && is_array( $value[ 'input' ] ) && ! empty( $value[ 'input' ] ) ){
			
			$test_form["input"] = $value[ 'input' ];
			
			$html .= '<h5><strong>Request Parameters</strong></h5>';
			$html .= '<p>The body of the request should contain the following fields</p>';

			$h = '<table class="table table-striped"><thead><th>Request</th><th>Type</th><th>Description</th></thead><tbody>';
			
			foreach( $value[ 'input' ] as $k => $v ){
				$h .= '<tr>';
					$h .= '<td class="col-md-2"><strong>'. $k . '</strong></td>';
					$h .= '<td class="col-md-2"><i>' . ( isset( $v[ 'type' ] ) ? $v[ 'type' ] : '' );
					
					$rtype = ( isset( $v[ 'request_type' ] ) ? $v[ 'request_type' ] : 'post' );
					$h .= '<br>' . $rtype;
					
					//$hash_values[] = $k;
					
					$h .= '</i></td>';
					$h .= '<td>';
						$h .= isset( $v[ 'description' ] ) ? $v[ 'description' ] : '';
						if( isset( $v["after_description"] ) && $v["after_description"] ){
							$h .= '<div>'. $v["after_description"] .'</div>';
						}
						$h .= isset( $v[ 'options' ] ) ? '<pre>'. __get_json_view2( $v[ 'options' ] ).'</pre><br>' : '';
						$h .= ( isset( $v[ 'default_value' ] ) ? '<strong>Default Value</strong>: ' . $v[ 'default_value' ] : '' );
						if( isset( $v["required"] ) && $v["required"] ){
							$h .= '<div style="color:red;"><i>*required</i></div>';
						}
					$h .= '</td>';
				$h .= '</tr>';
			}
			$h .= '</tbody></table>';
		}
		
		if( 1 ){
			$tables2 = '';
			if( isset( $value[ 'tables' ] ) && ! empty( $value[ 'tables' ] ) ){
				$tables2 = rawurlencode( json_encode( $value[ 'tables' ] ) );
			}
			
			if( ! isset( $default[ $value[ 'request_type' ] ] ) && isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ] ) ){
				
				$data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ][ 'api_method' ][ 'default_value' ] = $value[ 'api_method' ];
				
				$test_form["default_input"] = $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ];
				
				$read_requests = '<br /><h5 class="card-titlex"><strong>Default Values</strong></h5>';

				$read_requests .= '<table class="mb-0 table table-bordered">';
				$read_requests .= '<thead><tr>';
					$read_requests .= '<th>Field</th>';
					$read_requests .= '<th>Type</th>';
					$read_requests .= '<th>Description</th>';
				$read_requests .= '</tr></thead>';
				$read_requests .= '<tbody>';
				foreach( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ] as $k => $v ){
					
					switch( $v["type"] ){
					case "json":
						$test_form["default_input"][ $k ][ 'options' ] = array();
					break;
					}
					
					switch( $k ){
					case "api_hash_value":
						if( isset( $v[ 'default_hash_values' ] ) ){
							$hash_values = array_merge( $hash_values, $v[ 'default_hash_values' ] );
						}
						$v[ 'description' ] .= '<pre>' . implode( " + " , $hash_values ) . '</pre>';
					break;
					}
					
					if( isset( $value["default_settings"][ $k ] ) ){
						$v[ 'options' ] = $value["default_settings"][ $k ];
						$test_form["default_input"][ $k ][ 'options' ] = $v[ 'options' ];
					}
					
					$read_requests .= '<tr>';
						$read_requests .= '<td>'. $k .'</td>';
						$read_requests .= '<td>'. ( isset( $v[ 'type' ] ) ? $v[ 'type' ] : '' );
						$read_requests .= ( isset( $v[ 'request_type' ] ) ? '<br>' . $v[ 'request_type' ] : '' );
						$read_requests .= '</td>';
						$read_requests .= '<td>'. ( isset( $v[ 'description' ] ) ? str_replace( '{tables}', $tables2, $v[ 'description' ] ) : '' );

							$read_requests .= isset( $v[ 'options' ] ) ? '<pre>'. __get_json_view2( $v[ 'options' ] ) .'</pre>' : '<br>';
							$read_requests .= ( isset( $v[ 'after_description' ] ) ? $v[ 'after_description' ] : '' );
							
						$read_requests .= ( isset( $v[ 'default_value' ] ) ? '<strong>Default Value</strong>: ' . $v[ 'default_value' ] : '' );
						
						if( isset( $v["required"] ) && $v["required"] ){
							$read_requests .= '<div style="color:red;"><i>*required</i></div>';
						}
						
						$read_requests .= '</td>';
					$read_requests .= '</tr>';
				}
				$read_requests .= '</tbody>';
				$read_requests .= '</table>';

				if( isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'note' ] ) ){
					$read_requests .= '<br><div><strong>Note:</strong> '. $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'note' ] .'</div>';

				}

				$default[ $value[ 'request_type' ] ] = $read_requests;
			}
			
            if( isset( $default[ $value[ 'request_type' ] ] ) )$h .= $default[ $value[ 'request_type' ] ];
			$h .= '</div></div><br />';

			$html .= $h;
		}
	}
    $html .= '</div>';
	
	if( ! empty( $test_form ) ){
		if( ! empty( $hash_values ) ){
			$test_form[ "default_input" ][ "api_hash_key" ] = array( "type" => "string", "required" => 1, "request_type" => "GET", "after_description" => "<strong style='color:red;'>This parameter will not be sent as part of the API request.<br />It will only be used to generate the API_HASH_VALUE</strong>" );
		}
		
		$html = '<button class="btn btn-sm btn-default pull-right" onclick="nwTestAPI.sendTestRequest();">Send Test Request <i class="icon-external-link"></i></button>' . $html;
		
		//echo '<pre>'; print_r( $test_form ); echo '</pre>';
	}
    ?>
<div class="row">
    <div class="col-md-12" id="doc-container">
        <?php echo $html; ?>
    </div>
</div>
    <?php
}

function __get_json_view2( $data, $margin = 7 ){
    $h = '';
    if( is_array( $data ) && ! empty( $data ) ){
		$margin += 7;
        foreach( $data as $k => $v ){
            $h .= '"'. $k .'"' . ': ';
            
            if( is_array( $v ) && ! empty( $v ) ){
                $h .= '{<br />';
                $h .= '<div style="margin-left:'. $margin .'px;">';
                    $h .= __get_json_view2( $v, $margin );
                $h .= '</div>';
                $h .= '},<br>';
            }else{
                $h .= ( is_array( $v ) ? '{}' : ( $v ? '"'. $v .'"' : '""' ) ) . ',<br>';
            }
        }
    }else{
        $h .= $data;
    }
    return $h;
}

?>
</div>
</div>
</div>
<script type="text/javascript">
    $( document ).ready( function(){
        $( '#api-docs' ).find('li a').click(function(){
            $( '#api-docs' ).find('li a').removeClass( "mm-active" );
            $(this).addClass( "mm-active" );
        });
    });
	
var nwTestAPI = {
	hash_values: <?php echo json_encode( $hash_values ); ?>,
	data: <?php echo json_encode( $test_form ); ?>,
	myWindow: '',
	init: function(){},
	sendTestRequest: function(){
		if( ! $.isEmptyObject( nwTestAPI.data ) ){
			if( nwTestAPI.myWindow ){
				nwTestAPI.myWindow.close();
			}
			
			var hget = '';
			var h = '';
			$.each( { "input":"input", "default_input":"default_input" }, function( k1, v1 ){
				if( nwTestAPI.data[ k1 ] ){
					$.each( nwTestAPI.data[ k1 ], function( k, v ){
						if( v.request_type ){
							
							var attr = '';
							if( v.required ){
								attr = ' required ';
							}
							var ad = ( v.after_description?v.after_description:'' );
							
							switch( v.request_type ){
							case "post":
							case "POST":
								if( v.type ){
									h += '<label>' + k + '</label><br />' + ad;
									
									switch( v.type ){
									case "json":
										var vv = ''
										if( nwTestAPI.storedValues[ k ] ){
											vv = nwTestAPI.storedValues[ k ];
											h += '<small style="color:green;"><i>auto pasted from clipboard</i></small><br />';
										}else{
											vv = JSON.stringify( v.options, undefined, 4 );
										}
										h += '<textarea name="'+ k +'" '+ attr +' class="form-control">' + vv + '</textarea><br /><br />';
									break;
									case "string":
										if( v.options && ! $.isEmptyObject( v.options ) ){
											h += '<select name="'+ k +'" '+ attr +' class="form-control">';
											$.each( v.options, function( k2, v2 ){
												h += '<option value="'+ k2 +'">'+ k2 +'</option>';
											});
											h += '</select><br /><br />';
										}else{
											h += '<input name="'+ k +'" type="text" class="form-control" value="" '+ attr +'/><br /><br />';
										}
									break;
									case "integer":
										h += '<input name="'+ k +'" type="number" class="form-control" value="'+ ( v.default_value?v.default_value:'' ) +'" '+ attr +'/><br /><br />';
									break;
									}
								}
							break;
							case "get":
							case "GET":
								if( v.type ){
									hget += '<label>' + k + '</label><br />' + ad;
									var vv = ( v.default_value?v.default_value:'' );
									
									switch( v.type ){
									case "string":
										if( v.options && ! $.isEmptyObject( v.options ) ){
											hget += '<select name="'+ k +'" '+ attr +' class="form-control">';
											$.each( v.options, function( k2, v2 ){
												hget += '<option value="'+ k2 +'">'+ k2 +'</option>';
											});
											hget += '</select><br /><br />';
										}else{
											hget += '<input name="'+ k +'" type="text" class="form-control" value="'+ vv +'" '+ attr +'/><br /><br />';
										}
									break;
									case "integer":
										hget += '<input name="'+ k +'" type="number" class="form-control" value="'+ vv +'" '+ attr +'/><br /><br />';
									break;
									}
								}
							break;
							}
						}
					});
				}
			});
			var hend = '<input type="submit" value="Send Test Request" class="btn blue" />';
			hend += '</form>';
			
			var h2 = '';
			
			if( hget ){
				if( h ){
					h2 = '<h3>STEP 2: POST PARAMETERS</h3><form method="post" id="final-req-form">' + h + hend;
					h = '<form id="get-req-form"><h3>STEP 1: GET PARAMETERS</h3>';
					hget += '<input type="submit" value="Save GET Parameters & Proceed to Step 2" class="btn blue" /></form>';
				}else{
					h = '<form  method="get" id="final-req-form" action="'+ nwTestAPI.data.endpoint +'">';
					hget += hend;
				}
				
				h += hget;
			}else{
				h = '<form method="post" id="final-req-form" action="'+ nwTestAPI.data.endpoint +'">' + h + hend;
			}
			
			nwTestAPI.myWindow = window.open("", "testApiRequest", 'toolbar=no, location=no, menubar=no, resizable=yes, height=520, width=1000');
			
			var header = '';
			$("link[href$='.css']").each(function(){
				header += '<link href="'+ $(this).attr('href') +'" rel="stylesheet" type="text/css" />';
			});
			
			nwTestAPI.myWindow.document.write("<html><title>"+ nwTestAPI.data.title +": Test API Request</title>"+ header +"<body style='margin:0; padding:0;'><div class='row'><div class='col-md-8 col-md-offset-2'><div class='main-card mb-3 card'><div class='card-body'><h1>"+ nwTestAPI.data.title +"</h1><div id='test-request-con'>" + h + "<div id='test-request-con-2' style='display:none;'>"+ h2 +"</div></div></div></div></div></div></body></html>");
			
			$( nwTestAPI.myWindow.document ).ready(function() {
				if( ! $.isEmptyObject( sessionStorage ) ){
					$.each(sessionStorage, function( kx, vx ){
						$( nwTestAPI.myWindow.document )
						.contents()
						.find("input[name='"+ kx +"']")
						.val( vx );
					});
				}
				
				$( nwTestAPI.myWindow.document )
				.contents()
				.find("input")
				.blur(function(){
					if( $(this).val() ){
						sessionStorage[ $(this).attr('name') ] = $(this).val();
					}
				});
				
				$( nwTestAPI.myWindow.document )
				.contents()
				.find("form#get-req-form")
				.submit(function(){
					$(this).hide();
					
					$( nwTestAPI.myWindow.document )
					.contents()
					.find('#test-request-con-2')
					.show();
					
					return false;
				});
				
				$( nwTestAPI.myWindow.document )
				.contents()
				.find("form#final-req-form")
				.submit(function(){
					
					var $form2 = $( nwTestAPI.myWindow.document ).contents().find("form#final-req-form");
					var $form1 = $( nwTestAPI.myWindow.document ).contents().find("form#get-req-form");
					var $formFinal = $form2;
					
					if( $form1 && $form1.length > 0 ){
						$formFinal = $form1;
					}
					
					var concat_string = $formFinal.find('input[name="api_hash_key"]').val();
					$formFinal.find('input[name="api_hash_key"]').remove();
					
					if( ! $.isEmptyObject( nwTestAPI.hash_values ) ){
						$.each( nwTestAPI.hash_values, function( k1, v1 ){
							if( $form1 && $form1.length > 0 && $form1.find('[name="'+ v1 +'"]').val() ){
								concat_string = concat_string.concat( $form1.find('[name="'+ v1 +'"]').val() );
							}
							
							if( $form2.find('[name="'+ v1 +'"]').val() ){
								concat_string = concat_string.concat( $form2.find('[name="'+ v1 +'"]').val() );
							}
						});
					}
					
					$formFinal.find('input[name="api_hash_value"]').val( md5( concat_string ) );
					
					if( $form1 && $form1.length > 0 ){
						//prepare url
						$(this).attr("action", nwTestAPI.data.endpoint +'?'+ $form1.serialize() );
					}
				});
			});
			/* 
			nwTestAPI.myWindow.document.getElementById("get-req-form").onsubmit = function() {
				nwTestAPI.myWindow.document.getElementById("get-req-form").serialize();
				return false;
			};*/
		}
	},
	setWhere: function( id ){
		nwTestAPI.storedValues["where"] = $('textarea#' + id ).val();
	},
	storedValues: {},
	postForm: '',
};
</script>