<?php

class Freedom_Client {

    private $scopes;
    private $clientId;
    private $authClass;
    private $accessToken = null;
    private $client;

    public function __construct ()
    {
        $this->authClass = new Freedom_Oauth;
    }

    public function authenticate($data)
    {
        return $this->authClass->authenticate($data);
    }

    public function register($data)
    {
        return $this->authClass->register($data);
    }

    public function setAccessToken($accessToken)
    {
        if ($accessToken === 'null') {
            $accessToken = null;
        }

        $this->authClass->setAccessToken($accessToken);
    }

    public function getAccessToken()
    {
        $token = $this->authClass->getAccessToken();
        return (null == $token || 'null' == $token || '[]' == $token) ? null : $token;
    }

    public function setClientId($id)
    {
        $this->clientId = $id;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setScopes($scopes = [])
    {
        $this->scopes = implode(',', $scopes);
    }
}

