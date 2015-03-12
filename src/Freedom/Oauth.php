<?php

class Freedom_OAuth {
    protected $config;
	protected $scopes;
	protected $clientId;
	protected $_server;
	protected $port;
	protected $server;
    protected $accessToken = null;
    protected $request;

    function __construct($server = NULL, $port = NULL)
    {
        $this->config = include('configuration.php');
        if(!$server) {
            $server = $this->config['auth_server']['url'];
        }
        if(!$port) {
            $port = $this->config['auth_server']['port'];
        }

        $this->server = $server;
        $this->port = $port;
        $this->_server = $this->server . ':' . $this->port;

        $this->scopes = $this->config['basic_scopes'];
        $this->request = new Freedom_HttpRequest($this->_server);
    }

    public function getUserInfo()
    {
        if(!$this->accessToken) {
            throw new \Exception ('Missing access token');
        }

        $response = array();
        $this->request->setQueryString([
                'access_token' => $this->accessToken,
                'self' => true
            ]);
        $this->request->get('/user');
        $response = json_decode($this->request->response['data'], true);
        $response['users'][0]['app_data'] = $response['users'][0]['data_' . $this->clientId];
        unset($response['users'][0]['data_' . $this->clientId]);
        return $response;
    }


    private function login($payload, $source = 'google')
    {
        $payload['app_id'] = $this->clientId;
        $payload['source'] = $source;
        $this->request->setPayload($payload);
        $this->request->post('/auth/login');

        if($this->request->response['statusCode'] !== 200) {
            throw new \Exception ($this->request->response['data']);
        }
        return json_decode($this->request->response['data'], true);
    }

    private function getRequestToken($scopeToken)
    {
        $payload['app_id'] = $this->clientId;
        $payload['user_id'] = $scopeToken['user_data']['_id'];
        $payload['scope_token'] = $scopeToken['scope_token'];
        $payload['scopes'] = $this->scopes;
        $this->request->setQueryString($payload);
        $this->request->get('/auth/request_token');

        if($this->request->response['statusCode'] !== 200) {
            throw new \Exception ($this->request->response['data']);
        }

        return json_decode($this->request->response['data'], true);
    }


    public function authenticate($payload = array(), $source = 'google')
    {
        $response = array();
        if ($source === 'google') {
            if($payload['email'] === NULL || $payload['google_access_token'] === NULL) {
                throw new \Exception ('Missing requires');
            }
        } else {
            if($payload['email'] === NULL || $payload['password'] === NULL)
                throw new \Exception ('Missing requires');
        }

        if(!$this->clientId) {
            throw new \Exception ('no client id');
        }

        $scopeToken = $this->login($payload, $source);
        if($scopeToken['user_data']['data_'.$this->clientId]) {
            $scopeToken['app_data'] = $scopeToken['user_data']['data_'.$this->clientId];
            unset($scopeToken['user_data']['data_'.$this->clientId]);

            if(isset($scopeToken['app_data']['roles'])) {
                $roles = $scopeToken['app_data']['roles'];
            } else {
                $roles = $this->config['basic_roles'];
            }

            $scopes = [];
            foreach ($roles as $role) {
                $scopes = array_merge($scopes, $this->config['scopes'][$role]);
            }
            $this->setScopes($scopes);
        }
        $requestToken = $this->getRequestToken($scopeToken);
        $this->request->setQueryString([
                'app_id' => $this->clientId,
                'user_id' => $scopeToken['user_data']['_id'],
                'request_token' => $requestToken['request_token'],
            ]);
        $this->request->get('/auth/access_token');
        $this->accessToken = json_decode($this->request->response['data'], true)['access_token'];
        $this->request->addHeader(array('X-ACCESS-TOKEN' => $this->accessToken));
        $response = $scopeToken;
        return $response;
    }

    public function register($payload = array())
    {
        if(!$payload['email'] || !$payload['lname'] || !$payload['fname'] || !$payload['birthdate']) {
            throw new \Exception ('Missing requires');
        }

        $payload['app_id'] = $this->clientId;
        $roles = $this->config['basic_roles'];
        $scopes = [];
        foreach ($roles as $role) {
            $scopes = array_merge($scopes, $this->config['scopes'][$role]);
        }
        $this->setScopes($scopes);
        $payload['scopes'] = $this->scopes;

        if(!$this->clientId) {
            throw new \Exception ('no client id');
        }

        $this->request->setPayload($payload);
        $this->request->post('/user/register');

        if ($this->request->response['statusCode'] !== 200) {
            throw new \Exception ($this->request->response['data']);
        }

        $response = json_decode($this->request->response['data'], true);
        $this->accessToken = $response['access_token'];
        $this->request->addHeader(array('X-ACCESS-TOKEN' => $this->accessToken));
        return $response;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken ($accessToken)
    {
        $this->accessToken = $accessToken;
    }
}

/*fclient demonstration*/
//$fClient = new Freedom\Adapter\Freedom_Service_OAuth();
//$fClient->setAccessToken(Session::get('freedom_api_token'));
//$adapter = new Freedom\Adapter\Adapter($fClient);
//if($fClient->getAccessToken()) {
//      $adapter->getrevenue()
//} else {
//  //not authenticated
//}
