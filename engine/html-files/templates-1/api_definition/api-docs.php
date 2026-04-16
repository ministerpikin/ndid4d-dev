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
if( isset( $data[ 'api' ] ) && is_array( $data[ 'api' ] ) ){

    $new = array();

    foreach( $data[ 'api' ] as $k => $v ){
        if( ! isset( $new[ $v[ 'request_type' ] ][ $k ] ) )$new[ $v[ 'request_type' ] ][] = $v;
    }
    $first = 1;
    // echo '<pre>'; print_r( $new ); echo '</pre>';

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
                                            <a href="#'. $k1 .'" class=" custom-single-selected-record-button '. ( $first ? ' mm-active ' : '' ) .'" action="?module=&action=api_definition&todo=view_details2&html_replacement_selector=api-docs-preview" override-selected-record="'. $v1[ 'id' ] .'" >
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
            <div class="card-body" id="api-docs-preview">
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