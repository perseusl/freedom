<?php

class Freedom_Client {

    protected $authClass;
    protected $accessToken = null;
    protected $client;
    public $config;

    public function __construct ()
    {
        $this->config = new Freedom_Config;
        $this->authClass = new Freedom_Auth($this);
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
        $this->config->setClientId($id);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setScopes($scopes = [])
    {
        $this->authClass->setScopes($scopes);
    }

    public function setApplicationName($name)
    {
        $this->config->setApplicationName($name);
    }

    public function setClientSecret($secret)
    {
        $this->config->setClientSecret($secret);
    }

    public function setRedirectUri($uri)
    {
        $this->config->setRedirectUri($uri);
    }
}

