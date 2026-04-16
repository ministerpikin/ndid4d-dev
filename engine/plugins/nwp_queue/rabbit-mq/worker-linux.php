<?php

    error_reporting(E_ALL);

    ini_set('memory_limit', '2G');
    
    ini_set('log_errors', 1);
    ini_set('error_log', '/var/log/php/queue_worker.log');
    ini_set('display_errors', 0);
    sleep(60);

    require_once __DIR__ . '/../classes/vendor/autoload.php';

    $pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';

    if( ! class_exists("cNwp_app_core") ){
        require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
        //cNwp_app_core::loadENV();
    }else{
        exit("Missing Necessary Class");
    }

    
    use PhpAmqpLib\Connection\AMQPStreamConnection;

    $nwpApp = new cNwp_app_core();

    $host = defined('RABBITMQ_HOST') && RABBITMQ_HOST ? RABBITMQ_HOST : '192.168.222.143'; // Problems with loadENV on the 10.1.44.20 server (hot quick fix)
    $port = defined('RABBITMQ_PORT') && RABBITMQ_PORT ? RABBITMQ_PORT : 5673;

    function exception_handler($exception) {
        $timestamp = date('Y-m-d H:i:s');
        $message = "[$timestamp] Uncaught exception: " . $exception->getMessage();
        $message .= "\n\tFile: ".$exception->getFile();
        $message .= "\n\tLine: ".$exception->getLine();
        $message .= "\n\tStack Trace: ".$exception->getTraceAsString();
        $message .= "\n";
        error_log($message);
        fwrite(STDERR, $message . PHP_EOL);
        exit;
    }

    function error_handler($errno, $errstr, $errfile, $errline) {
        $timestamp = date('Y-m-d H:i:s');
        $errorMessage = "[$timestamp] Error [$errno]: $errstr in $errfile on line $errline";
        error_log($errorMessage);
        fwrite(STDERR, $errorMessage . PHP_EOL);
        return true;
    }

    set_exception_handler('exception_handler');

    set_error_handler('error_handler');

    $callback = function ( $msg ) use ($pagepointer, $nwpApp) {
        try{
            $data = json_decode( $msg->body, true);

            $exception = '';

            if( isset($data['args']) && $data['args'] && isset($data['queue']) && $data['queue'] ){

                $config = [ 'pagepointer' => $pagepointer, 'returnObject' => 1, 'skip_headers' => 1, 'doNotLoadSession' => 1, 'openConnection' => 1, 'loggedIn' => 1 ];

                $headless_mode = isset($data['headless_mode']) && $data['headless_mode'] ? $data['headless_mode'] : false;

                if( !$headless_mode ){
                    $_GET = [];
                    $_GET['action'] = 'nwp_queue';
                    $_GET['todo'] = 'execute';
                    $_GET['nwp_action'] = 'job_queue';
                    $_GET['nwp_todo'] = 'update_queue_item';
                    $_GET['default'] = 'default';

                    $_POST = ['id' => $data['queue'], 'status' => 'running' ];

                    $return = json_decode( $nwpApp->app( $config ), true );
                    usleep(20000);
                }else{
                    $return['saved_record_id'] = 1;
                }


                if( isset($return['saved_record_id']) && $return['saved_record_id'] ){
                  
                    $_GET = [];
                    $_POST = [];
                    $p = array_chunk( preg_split('(:::|@@)', $data['args'] ), 2 );
                    if( ! empty( $p ) ){
                        foreach( $p as $p1 ){
                            if( isset( $p1[0] ) && isset( $p1[1] ) ){
                                switch( $p1[0] ){
                                    case "user_id":
                                        $_POST[ $p1[0] ] = $p1[1];
                                    break;
                                    case "reference":
                                        $_POST[ 'id' ] = $p1[1];
                                    break;
                                    case "session_id":
                                        if( $p1[1] )$GLOBALS[ $p1[0] ] = $p1[1];
                                    break;
                                    case 'operation':
                                    default:
                                        $_GET[ $p1[0] ] = $p1[1];
                                    break;
                                }
                            }
                        }
                    }

                    if( ! ( isset( $_POST["user_id"] ) && $_POST["user_id"] ) ){
                        $_GET['default'] = "default";
                    }else{
                        if( $_POST["user_id"] == "anonymous" ){
                            $_GET['default'] = "default";
                        }else{
                            $config['defaultUser'] = $_POST['user_id'];
                            $config['loggedIn'] = 1;
                        }
                    }

                    $starttime = microtime(true);
                    echo "\n [". date('d-m-Y H:i:s') ."] Executing Queue Task ==== [ - ] \n";
                    $return = json_decode( $nwpApp->app( $config ), true );
                    usleep(20000);
                    echo "\n [". date('d-m-Y H:i:s') ."] Finished One Task ==== [ + ] \n";

                    if( !$headless_mode ){
                        $_GET = [];
                        $_GET['action'] = 'nwp_queue';
                        $_GET['todo'] = 'execute';
                        $_GET['nwp_action'] = 'job_queue';
                        $_GET['nwp_todo'] = 'update_queue_item';
                        $_GET['default'] = 'default';

                        $_POST = ['id' => $data['queue'], 'status' => 'complete', 'execution_time' => ((microtime(true) - $starttime) / 1000) ];

                        $return = json_decode( $nwpApp->app( $config ), true );
                        usleep(20000);
                    }
                 
                    if( !(isset( $return['saved_record_id'] ) && $return['saved_record_id'] ) && !$headless_mode ){
                        $exception = "\n [-] Failed to update Queue Item Status: *Completed* -> #Ref-". $data['queue'] ." \n";
                        if( isset($return['inf']) && $return['inf'] ){
                            $exception = "\tDetail: ". $return['inf'];
                        }else{
                            $exception = 'Response: ' . json_encode($return, JSON_PRETTY_PRINT);
                        }
                        throw new Exception($exception, 1);
                    }
                }else{
                    $exception = "\n [-] Failed to Start Queue Item Status: *Running* -> #Ref-". $data['queue'] ." \n";
                    if( isset($return['inf']) && $return['inf'] ){
                        $exception = "\tDetail: ". $return['inf'];
                    }else{
                        $exception = 'Response : ' . json_encode($return, JSON_PRETTY_PRINT);
                    }
                    throw new Exception($exception, 1);
                }
            }else{
                throw new Exception("Unable to Parse Received Queue Data", 1);
            }

        }catch(Exception $e){
            $nexception = new Exception( "\n [*] [QUEUE CALLBACK ERROR][". $e->getMessage() ."] \n ", $e->getCode(), $e );
            exception_handler( $nexception );
        }

        $msg->ack();
    };

    try{
        $connection = new AMQPStreamConnection($host, $port, 'guest', 'guest');
        
        $channel = $connection->channel();

        $channel->queue_declare('task_queue', false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";
       

        $channel->basic_qos(null, 1, null);
       
        $channel->basic_consume('task_queue', '', false, false, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        
        $connection->close();
        
    }catch(Exception $e){
        $nexception = new Exception( "Connection Exception: " . $e->getMessage(), $e->getCode(), $e );
        exception_handler( $nexception );
    }

?>