<?php

class Freedom_Service {
	  protected $adapter;
	  protected $request;

    public function __construct($client)
    {
		    $this->config = include('configuration.php');

		    $this->adapter = $client;
		    $this->request = new Freedom_HttpRequest($this->config['backend_server']['url'] . ':' . $this->config['backend_server']['port']);
		    $this->request->addHeader(array('X-ACCESS-TOKEN' => $this->adapter->getAccessToken()));
	  }

    public function requires($param)
    {

    }
}
