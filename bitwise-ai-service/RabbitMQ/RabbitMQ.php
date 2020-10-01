<?php
// require_once('./vendor/autoload.php');

// Include the supporting files
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ {

	function __construct($host, $port, $username, $password)
	{
		$this->host     = $host;
		$this->port     = $port;
		$this->username = $username;
		$this->password = $password;

		$this->connection = new AMQPStreamConnection($this->host, $this->port, $this->username, $this->password); //Creating the connection.
	}

	public function Channel()
	{
		return $this->connection->channel();
	}


	public function ExchangeDeclration($exchange_name='', $exchange_type='')
	{
		$this->channel = $this->Channel();
		$this->channel->exchange_declare($exchange_name, $exchange_type, true, false, false);
	}

	public function QueueDeclration($queue_name='')
	{
		$this->channel = $this->Channel();
		$this->channel->queue_declare( $queue_name, false, true, false, false);
	}

	public function ChannelClose()
	{
		// $this->channel = $this->Channel();
		$this->channel->close();
	}

	public function ConnectionClose()
	{
		$this->connection->close();
	}

	public function QueueBind($queue_name='', $exchange_name='', $binding_key='')
	{
		$this->channel->queue_bind($queue_name, $exchange_name, $binding_key);
	}

	public function BasicPublish($msg='', $exchange_name='', $route_key='')
	{
		$msg = new AMQPMessage( $msg );
		$s = $this->channel->basic_publish($msg, $exchange_name, $route_key);
		$this->channel->close();
		$this->connection->close();
	}
}
