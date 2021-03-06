<?php
require_once(config_get('class_path') . 'MantisPlugin.class.php');
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


function amqp($connector_name, $component, $resource, $state, $state_type, $output, $display_name)
{
    // Configurations
    
    $host     = plugin_config_get('host');
    $port     = plugin_config_get('port');
    $user     = plugin_config_get('user');
    $pass     = plugin_config_get('password');
    $vhost    = plugin_config_get('vhost');
    $exchange = plugin_config_get('exchange');
    $activate = plugin_config_get('activate');
    
    
    // Connection
    $conn = new AMQPConnection($host, $port, $user, $pass, $vhost);
    $ch   = $conn->channel();
    
    // Declare exchange (if not exist)
    // exchange_declare($exchange, $type, $passive=false, $durable=false, $auto_delete=true, $internal=false, $nowait=false, $arguments=null, $ticket=null)
    $ch->exchange_declare($exchange, 'topic', false, true, false);
    
    // Create Canopsis event, see: https://github.com/capensis/canopsis/wiki/Event-specification
    $msg_body = array(
        "timestamp" => time(),
        "connector" => "cli",
        "connector_name" => $connector_name,
        "event_type" => "log",
        "source_type" => "resource",
        "component" => $component,
        "resource" => $resource,
        "state" => $state,
        "state_type" => $state_type,
        "output" => $output,
        "display_name" => $display_name,
        'connection_timeout' => 10,
        'read_write_timeout' => 3
    );
    $msg_raw  = json_encode($msg_body);
    
    // Build routing key
    $msg_rk = $msg_body['connector'] . "." . $msg_body['connector_name'] . "." . $msg_body['event_type'] . "." . $msg_body['source_type'] . "." . $msg_body['component'];
    
    if ($msg_body['source_type'] == "resource")
        $msg_rk = $msg_rk . "." . $msg_body['resource'];
 
    
    $msg = new AMQPMessage($msg_raw, array(
        'content_type' => 'application/json',
        'delivery_mode' => 2
    ));
    
    // Publish Event
   
    if ($activate == "1") {
        $ch->basic_publish($msg, $exchange, $msg_rk);
    }
    
    // Close connection
    $ch->close();
    $conn->close();
}
?>
