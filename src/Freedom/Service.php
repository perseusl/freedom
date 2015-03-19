<?php

class Freedom_Service {

	protected $adapter;
	protected $request;

    public function __construct(Freedom_Client $client)
    {
		$this->adapter = $client;
		$this->request = new Freedom_HttpRequest($this->adapter->config->getBasePath());
        $this->request->addHeader(
            array('X-ACCESS-TOKEN' => $this->adapter->getAccessToken())
        );
    }

    public function requires($param)
    {

    }
}
