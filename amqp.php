<?php
require_once __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


function amqp($connector_name,$component,$resource,$state,$state_type,$output,$display_name){
// Configurations
$host = "172.17.6.203";
$port = 5672;
$user = "guest";
$pass = "guest";
$vhost = "canopsis";
$exchange = "canopsis.events";

// Connection
$conn = new AMQPConnection($host, $port, $user, $pass, $vhost);
$ch = $conn->channel();

// Declare exchange (if not exist)
// exchange_declare($exchange, $type, $passive=false, $durable=false, $auto_delete=true, $internal=false, $nowait=false, $arguments=null, $ticket=null)
$ch->exchange_declare($exchange, 'topic', false, true, false);

// Create Canopsis event, see: https://github.com/capensis/canopsis/wiki/Event-specification
$msg_body = array(
	"timestamp"		=> time(),
	"connector"		=> "cli",
	"connector_name"	=> $connector_name,
	"event_type"		=> "log",
	"source_type"		=> "resource",
	"component"		=> $component,
	"resource"		=> $resource,
	"state"			=> $state,
	"state_type"		=> $state_type,
	"output"		=> $output,
	"display_name"		=> $display_name
);
$msg_raw = json_encode($msg_body);

// Build routing key
$msg_rk = $msg_body['connector'] . "." . $msg_body['connector_name'] . "." . $msg_body['event_type'] . "." . $msg_body['source_type'] . "." . $msg_body['component'];

if ($msg_body['source_type'] == "resource")
	$msg_rk = $msg_rk . "." . $msg_body['resource'];

echo "JSON Event:  " . $msg_raw . "\n";
echo "Routing-key: " . $msg_rk . "\n";

$msg = new AMQPMessage($msg_raw, array('content_type' => 'application/json', 'delivery_mode' => 2));

// Publish Event
$ch->basic_publish($msg, $exchange, $msg_rk);

// Close connection
$ch->close();
$conn->close();
}
?>