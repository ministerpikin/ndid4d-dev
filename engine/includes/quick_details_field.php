<?php
    if( isset( $field['quick_details_field'] ) && $field['quick_details_field'] && isset( $aRow[ $field['quick_details_field'] ] ) ){
        $tb = $table;
        if( isset( $field['quick_details_table'] ) && $field['quick_details_table'] )$tb = $field['quick_details_table'];
        $cell_data = '<a href="?action='.$tb.'&todo=quick_details_view&id='.$aRow[ $field['quick_details_field'] ].'" class="quick-details-field" title="Click to View Details" data-id="'.$aRow[ $field['quick_details_field'] ].'" action="?action='.$tb.'&todo=quick_details_view&id='.$aRow[ $field['quick_details_field'] ].'">'.$cell_data.'</a>';
    }else{
        if( isset( $field['preview_url'] ) && isset( $field['preview_fields'] ) && is_array( $field['preview_fields'] ) ){
            $p_link = '';
            foreach( $field['preview_fields'] as $f ){
                if( isset( $aRow[ $f ] ) && $aRow[ $f ] ){
                    if($p_link)$p_link .= '-'.$aRow[ $f ];
                    else $p_link = $aRow[ $f ];
                }
            }
            $cell_data = '<a href="'.$field['preview_url'].clean1($p_link).'" target="_blank" title="Click to Preview">'.$cell_data.'</a>';
        }
    }
?>