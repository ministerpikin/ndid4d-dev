<style type="text/css">
    .text-more{
        font-size:0.9em;
        font-style:italic;
    }
</style>
<div class="row">
    <?php
        $count = 0;
        
        if( isset( $data['notice'] ) && $data['notice'] ){
        foreach( $data['notice'] as $key => $v ){
            
            if( ! ( $count % 3 ) ){
            ?>
            </div><br /><div class="row">
            <?php
            }
            ++$count;
    ?>
    <div class="span4" >
        <table class="table table-bordered table-striped" style="margin-bottom:4px;">
            <thead>
              <tr>
                <th><?php echo $v['title']; ?></th>
              </tr>
            </thead>
        </table>
        <div style="max-height:176px; overflow-y:auto;">
        <table class="table table-bordered table-striped" style="margin-bottom:4px;">
            <tbody>
                <?php
                    if( isset( $v['data'] ) && $v['data'] ){
                        foreach( $v['data'] as $id => $val ){
              ?>
                <tr>
                <td>
                    <small>
                    <?php if( isset( $val[ $v['date'] ] ) ){ ?>
                    <code class="pull-right"><?php 
                        switch( $v['date'] ){
                        case 'expiry_date_timestamp':
                            echo date( "d-M-Y", doubleval( $val[ $v['date'] ] ) ); 
                        break;
                        default:
                            echo date( "jS/M H:i", doubleval( $val[ $v['date'] ] ) ); 
                        break;
                        }
                        
                    ?></code>
                    <?php } ?>
                    <strong>
                    <?php 
                    switch( $v['field'] ){
                    case 'total':
                        $tk = convert_currency( $val[ $v['field'] ] );
                    break;
                    default:
                        $tk = $val[ $v['field'] ];
                    break;
                    }
                    if( isset( $v['id_field'] ) && isset( $val[ $v['id_field'] ] ) && isset( $v['table'] ) ){
                        $tk = '<a href="?action='.$v['table'].'&todo=quick_details_view&id='.$val[ $v['id_field'] ].'" class="quick-details-field" title="Click to View Details" data-id="'.$val[ $v['id_field'] ].'" action="?action='.$v['table'].'&todo=quick_details_view&id='.$val[ $v['id_field'] ].'">'.$tk.'</a>';
                    }
                    echo $tk;
                    ?>
                    </strong>
                    <?php 
                        if( isset( $v['field2'] ) && isset( $val[ $v['field2'] ] ) ){
                            echo '<div class="text-more">';
                                $tk = $val[ $v['field2'] ];
                                echo $tk;
                            echo '</div>'; 
                        }
                    ?>
                  </small>
                </td>
              </tr>
              <?php
                        }
                    }
               ?>
            </tbody>  
        </table>
        </div>
    </div>
    <?php
        }
        }
    ?>
    
</div>