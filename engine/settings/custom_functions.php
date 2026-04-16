<?php
	// function itemize_by_key( $o = array(), $key = '' ){
	// 	if( $o && $key ){
	// 		$_o = [];
	// 		foreach ($o as $value) {
	// 			if( isset( $value[ $key ] ) ){
	// 				$_o[ $value[ $key ] ] = $value;

	// 			}else throw new Exception("Given Key :[". $key ."] is not present in Array", 1);
	// 		}
	// 		if( $_o) return $_o;
	// 	}
	// 	return $o;
	// }

	// function itemize_by_key_multi( $o = array(), $key = '' ){
	// 	if( $o && $key ){
	// 		$_o = [];
	// 		foreach ($o as $value) {
	// 			if( isset( $value[ $key ] ) && $value[ $key ] ){
	// 				$_o[ $value[ $key ] ][] = $value;

	// 			}else throw new Exception("Given Key :[". $key ."] is not present in Array", 1);
	// 		}
	// 		if( $_o) return $_o;
	// 	}

	// 	return $o;
	// }

	// function null_array( $o ){
	// 	if( $o ){
	// 		$keys = array_keys( $o );
	// 		if( $keys ){
	// 			foreach( $keys as $key ){
	// 				if( isset($o[ $key ]) && $o[ $key ] ){
	// 					return false;
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return true;
	// }

	// function unset_null_array( $o ){
	// 	if( $o ){
	// 		if( is_array( $o ) ){
	// 			foreach ($o as $key => $value) {
	// 				if( is_array($value) ){
	// 					if( null_array( $value ) ){
	// 						unset( $o[ $key ] );
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return $o;
	// }

?>