<?php
	
	$username = "elastic";
	if( defined( 'ES_USERNAME' ) && ES_USERNAME ){
		$username = ES_USERNAME;
	}
	$password = "3gwrx=UgfzTDG2bt08vB";
	if( defined( 'ES_PASSWORD' ) && ES_PASSWORD ){
		$password = ES_PASSWORD;
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

	require 'vendor/autoload.php';

	use Elastic\Elasticsearch;

	$client = Elastic\Elasticsearch\ClientBuilder::create()
				->setHosts(['https://localhost:9200'])
				->setBasicAuthentication( $username, $password )
				->setCABundle( __DIR__ . '/certs/http_ca.crt')
				->build();
	$response = $client->info();
?>