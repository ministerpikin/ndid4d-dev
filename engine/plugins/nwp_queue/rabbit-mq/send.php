<?php
	require_once __DIR__ . '/vendor/autoload.php';
	
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	$host = 'localhost';
	$port = 5672;
	// $host = '172.19.140.23';

	$connection = new AMQPStreamConnection($host, $port, 'guest', 'guest');
	$channel = $connection->channel();

	$channel->queue_declare('hello', false, false, false, false);

	$msg = new AMQPMessage('Hello World!');
	$channel->basic_publish($msg, '', 'hello');

	echo " [x] Sent 'Hello World!'\n";

	$channel->close();

	$connection->close();
?>