<?php

class Freedom_Service_Visitor {

	protected $request;
	protected $adapter;

	public function __construct ($client)
	{
		$this->adapter = new Freedom_Adapter($client);
		$this->request = $this->adapter->getRequest(); //request instance
	}

	public function sendResetEmail($query)
    {
    	$query = $this->adapter->requires($query, ['email']);

    	$this->request->setQueryString($query);
    	$this->request->get('/send_reset_mail');

    	return $this->request->response;
    }

    public function resetEmail($payload)
    {
    	$payload = $this->adapter->requires($payload, [
    		'email',
    		'password',
    		'reset_token'
		]);

        if( gettype($payload) === 'string' ) {

            $message = [
                'message' => $payload,
                'code' => 500
            ];

            return $message;
        }

    	$this->request->setPayload($payload);
    	$this->request->post('/reset_password');

    	return $this->request->response;
    }

}
