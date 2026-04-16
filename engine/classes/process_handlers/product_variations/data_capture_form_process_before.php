<?php
	/**
	 * users Class
	 *
	 * @used in  				users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	users
	 */

	/*
	|--------------------------------------------------------------------------
	| users Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	if( isset( $this->class_settings[ 'form_maximum_value_limit' ] ) && isset( $values ) && ! empty( $values ) ){
		
		foreach( $this->class_settings[ 'form_maximum_value_limit' ] as $key => & $val ){
			if( isset( $values[ $key ] ) )
				$val = $val + $values[ $key ];
		}
		
		if( isset( $values[ 'product_variations006' ] ) )
			$this->class_settings[ 'form_maximum_value_limit' ][ 'product_variations007' ] = $values[ 'product_variations006' ];
		else
			$this->class_settings[ 'form_maximum_value_limit' ][ 'product_variations007' ] = $this->class_settings[ 'form_maximum_value_limit' ][ 'product_variations006' ];
	}else{
		$this->class_settings[ 'form_maximum_value_limit' ][ 'product_variations007' ] = 0;
	}
?>