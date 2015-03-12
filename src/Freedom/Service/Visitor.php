<?php

class Freedom_Service_Visitor extends Freedom_Service {

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
