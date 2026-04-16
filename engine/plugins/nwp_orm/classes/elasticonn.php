<?php
	
	$username = "elastic";
	if( defined( 'ES_USERNAME' ) && ES_USERNAME ){
		$username = ES_USERNAME;
	}
	$password = "3gwrx=UgfzTDG2bt08vB";
	if( defined( 'ES_PASSWORD' ) && ES_PASSWORD ){
		$password = ES_PASSWORD;
	}
	$hosts = "localhost";
	if( defined( 'ELASTIC_HOST' ) && ELASTIC_HOST ){
		$hosts = ELASTIC_HOST;
	}
	/*
		cOrm_elasticsearch::$esClient = ClientBuilder::create()
		    ->setBasicAuthentication( $username, $password );
			$host = defined('ELASTIC_HOST') && ELASTIC_HOST ? ELASTIC_HOST : '';
			if( $host ){
				cOrm_elasticsearch::$esClient->setHosts([$host]);
			}
			
			if( defined('ELASTIC_SSL_DIR') && ELASTIC_SSL_DIR ){
				cOrm_elasticsearch::$esClient->setCABundle( __DIR__ . ELASTIC_SSL_DIR );
			}

			cOrm_elasticsearch::$esClient->build();
	*/

	$pxx = dirname( dirname( __FILE__ ) ) . '/nwp_orm/';
	if( defined( $pxx . 'ES_VERSION_PATH' ) && $pxx . ES_VERSION_PATH ){
		require $pxx . ES_VERSION_PATH;
	}elseif( defined( 'ES_VERSION' ) && ES_VERSION ){
		require ES_VERSION;
		// require __DIR__."/../". ES_VERSION ."/vendor/autoload.php";
	}
	// require 'vendor/autoload.php';

	use Elastic\Elasticsearch\ClientBuilder;
	// use Elastic\Elasticsearch;

	try {
		// $client = Elastic\Elasticsearch\ClientBuilder::create()
		$client = ClientBuilder::create()
					->setHosts(['http://' . $hosts])
					->setBasicAuthentication( $username, $password )
					// ->setCABundle( __DIR__ . '/certs/http_ca.crt')
					->build();
		$response = $client->info();
	} catch (Exception $e) {
		$response[ 'error' ] = $e->getMessage();
	}
?>