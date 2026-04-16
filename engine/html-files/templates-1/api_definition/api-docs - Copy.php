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
    echo '<pre>'; print_r( $data ); echo '</pre>';
if( isset( $data[ 'api' ] ) && is_array( $data[ 'api' ] ) && isset( $data[ 'api' ][ 'api_methods' ] ) && is_array( $data[ 'api' ][ 'api_methods' ] ) ){

    $new = array();

    foreach( $data[ 'api' ][ 'api_methods' ] as $k => $v ){
        if( ! isset( $new[ $v[ 'request_type' ] ][ $k ] ) )$new[ $v[ 'request_type' ] ][ $k ] = $v;
    }
    $first = 1;

    $html = '';
    $html_side = '<ul class="vertical-nav-menu">';
        $html_side .= '<li class="app-sidebar__heading">REQUEST TYPES<i class="metismenu-state-icon pe-7s-angle-down icon-caret-down"></i></li>';
        $html_side .= '<li class="'. ( $first ? ' mm-active ' : '' ) .'">';

            $html_side .= '<ul class="sub-menu">';

    foreach( $new as $k => $v ){

        $html_side .= '<li class="app-sidebar__heading ">'. ucwords( $k ) .'<i class="metismenu-state-icon pe-7s-angle-down '. ( $first ? 'icon-caret-down' : 'icon-caret-left' ) .'"></i>';
        $html_side .= '</li>';
        

        $html_side .= '<li class=" mm-active">';
        $html_side .= '<ul class="sub-menu '. ( $first ? '' : ' hidden ' ) .'">';
        foreach( $v as $k1 => $v1 ){

            $html_side .= ' <li class="'. ( $first ? ' mm-active ' : '' ) .'">
                            <a href="#'. $k1 .'" class="'. ( $first ? ' mm-active ' : '' ) .'" >
                                '. $v1[ 'title' ] .'
                            </a>
                        </li>';
            if( $first )$first = 0;
        }
        $html_side .= '</ul>';
        $html_side .= '</li>';

    }

        $html_side .= '</ul>';
        
    $html_side .= ' </li>';

    
    $detault = array();
    $first = 1;

        $html_side .= '<li class="app-sidebar__heading">Methods<i class="metismenu-state-icon pe-7s-angle-down icon-caret-left"></i></li>';
        $html_side .= '<li>';
        $html_side .= '<ul class="sub-menu hidden">';

	foreach ( $data[ 'api' ][ 'api_methods' ] as $key => $value) {
        $id = $key;
        $html_side .= ' <li>
                        <a href="#'. $id .'" class="'. ( $first ? ' mm-active ' : '' ) .'" >
                            <i class="metismenu-icon"></i>
                            '. $value[ 'title' ] .'
                        </a>
                    </li>';
        if( ! $first ){
            $html .= '<br>';
            $html .= '<hr>';
            $html .= '<br>';
        }
        $html .= '<div id="'. $id .'">';

        if( ! isset( $default[ $value[ 'request_type' ] ] ) && isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ] ) ){

            $read_requests = '<br /><h5 class="card-title">Default Values</h5>';

            $read_requests .= '<table class="mb-0 table table-bordered">';
            $read_requests .= '<thead><tr>';
                $read_requests .= '<th>Field</th>';
                $read_requests .= '<th>Type</th>';
                $read_requests .= '<th>Description</th>';
            $read_requests .= '</tr></thead>';
            $read_requests .= '<tbody>';
            foreach( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'input' ] as $k => $v ){
                $read_requests .= '<tr>';
                    $read_requests .= '<td>'. $k .'</td>';
                    $read_requests .= '<td>'. ( isset( $v[ 'type' ] ) ? $v[ 'type' ] : '' ) .'</td>';
                    $read_requests .= '<td>'. ( isset( $v[ 'description' ] ) ? $v[ 'description' ] : '' );

                        $read_requests .= isset( $v[ 'options' ] ) ? '<pre>'. __get_json_view2( $v[ 'options' ] ) .'</pre>' : '<br>';
                    $read_requests .= ( isset( $v[ 'default_value' ] ) ? '<strong>Default Value</strong>: ' . $v[ 'default_value' ] : '' );
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

		$html .= '<h4><strong>'. $value[ 'title' ] . '</strong></h4>';

		if( isset( $value[ 'request_type' ] ) && $value[ 'request_type' ] ){
			if( isset( $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) && $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ){
				$html .= str_replace( '{name}', $value[ 'name' ], $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ] ) . '<br><br>';

				// $html .= $data[ 'default_settings' ][ $value[ 'request_type' ] ][ 'description' ];
			}
		}
			
		$html .= '
		<div class="note note-warning">
			<h4>Endpoints</h4>
			<p>'. $data[ 'default_settings' ][ 'endpoint' ] .'</p>
		</div>';

		if( isset( $value[ 'input' ] ) && ! empty( $value[ 'input' ] ) ){
			$html .= '<h5><strong>Request Parameters</strong></h5>';
			$html .= '<p>The Body of the request should contain the following fields</p>';

			$h = '
				<div class="card" >
				<div class="card-body">
					<table class="mb-0 table table-striped">
						<thead><th>Request</th><th>Type</th><th>Description</th></thead>
						<tbody>';
			foreach( $value[ 'input' ] as $k => $v ){
				$h .= '<tr>';
					$h .= '<td class="col-md-2"><strong>'. $k . '</strong></td>';
					$h .= '<td class="col-md-2"><i>' . ( isset( $v[ 'type' ] ) ? $v[ 'type' ] : '' );
                    $h .= '<br>' . ( isset( $v[ 'request_type' ] ) ? $v[ 'request_type' ] : '' );
					if( isset( $v[ 'required' ] ) && $v[ 'required' ] ){
						$h .= '<br /><span style="color:red;">required</span>';
					}
					$h .= '</i></td>';
					$h .= '<td>';
						$h .= isset( $v[ 'description' ] ) ? $v[ 'description' ] : '';
                        $h .= isset( $v[ 'options' ] ) ? '<pre>'. __get_json_view2( $v[ 'options' ] ).'</pre>' : '<br>';
                        $h .= ( isset( $v[ 'default_value' ] ) ? '<strong>Default Value</strong>: ' . $v[ 'default_value' ] : '' );
					$h .= '</td>';
				$h .= '</tr>';
			}
					$h .= '</tbody>
				</table>';
            if( isset( $default[ $value[ 'request_type' ] ] ) )$h .= $default[ $value[ 'request_type' ] ];
			$h .= '</div>
    			</div>
                <br />';


			$html .= $h;
		}
        $html .= '</div>';
        if( $first )$first = 0;
	}
    $html_side .= '</ul>';
    $html_side .= '</li>';
    $html_side .= '</ul>';
    // echo $html;
    ?>
<div class="row">
    <div class="col-md-3">
        <div class=" sidebar-shadow documentation">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <?php echo $html_side; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <?php echo $html; ?>
            </div>
        </div>
    </div>
</div>
    <?php
}

function __get_json_view2( $data, $margin = 7 ){
    $h = '';
    if( is_array( $data ) && ! empty( $data ) ){
        foreach( $data as $k => $v ){
            $h .= $k . ': ';
            if( is_array( $v ) && ! empty( $v ) ){
                $h .= '{<br />';
                $h .= '<div style="margin-left:'. $margin .'px;">';
                    $margin += 7;
                    $h .= __get_json_view2( $v, $margin );
                $h .= '</div>';
                $h .= '}<br>';
            }else{
                $h .= ( is_array( $v ) ? '{}' : ( $v ? $v : "''" ) ) . '<br>';
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
</script>