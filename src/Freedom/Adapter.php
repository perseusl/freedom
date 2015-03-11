<?php

define('_SERVER', 'apidev.freedom.tm');
define('_PORT', 8000);

class Freedom_Adapter {

    private $server;
    private $port;
    private $_server;
    private $accessToken = null;
    private $cache = array();
    private $cacheCount = 0;
    private $client;
    private $request;

    function __construct ($client, $server = _SERVER, $port = _PORT)
    {
        $this->client = $client;
        $this->server = $server;
        $this->port = $port;
        $this->_server = $this->server . ':' . $this->port;
        $this->request = new Freedom_HttpRequest($this->_server);
        $this->ready = true;

        $this->request->addHeader(
            [
                'X-ACCESS-TOKEN' => $this->client->getAccessToken()
            ]
        );
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function requires ($data, $requires, $opts = [])
    {
        $arr = array(); 

        if (gettype($data) !== 'array') {
           return 'missing data'; 
        }

        switch (gettype($requires)) {
            case 'string':
                if (!array_key_exists($requires, $data) || !isset($data[$e])) {
                    return 'missing [' . $requires . ']';
                }

                $arr[$requires] = $data[$requires];
                break;
            case 'array':
                foreach ($requires as $e) {
                    if (!array_key_exists($e, $data) || !isset($data[$e])) {
                        return 'missing [' . $e . ']';
                    }

                    $arr[$e] = $data[$e];
                }
                break;
            default:
                return 'missing requires';
        }

        switch (gettype($opts)) {
            case 'string':
                if (array_key_exists($opts, $data) || isset($data[$opts])) {
                    $arr[$opts] = $data[$opts];
                }

                break;
            case 'array':
                foreach ($opts as $e) {
                    if (array_key_exists($e, $data)) {
                        $arr[$e] = $data[$e];
                    }
                }
                break;
            default:
                return 'missing requires';
        }

        return $arr;
    }

    private function addCache($name, $val)
    {
        $this->cache[$name] = $val;

        $this->cacheCount++;
    }

    public function flushCache()
    {
        $this->cache = array();
        $this->cacheCount = 0;
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * FOLLOWING FUNCTIONS SHOULD HAVE IN THEIR HEADER                         *
     *  - X-ACCESS-TOKEN                                                       *
     * The X-ACCESS-TOKEN should be initialize after logging in.               *
     * Adding the header can be accomplished by calling                        *
     *  $this                                                                  *
     *      ->request                                                          *
     *      ->addHeader(                                                       *
     *          array(                                                         *
     *              'X-ACCESS-TOKEN' => '<access_token_here>'                  *
     *          )                                                              *
     *       );                                                                *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    public function getProspects($user_id, $payload = array())
    {
        $this->addCache(
            'requires', 
            $this->requires($user_id, null, array('page', 'limit'))
        );

        if (!$user_id) {
            $user_id = '';
        }

        $this->request->setQueryString($payload);
        $this->request->get('/prospects/'. $user_id);
        $this->addCache('getProspects', $this->request->response);

        return $this->request->response['data'];
    }

    public function searchProspects($key)
    {
        if (isset($key)) {
            return false; 
        }

        $this->request->get('/prospects/search/' . $key);
        $this->addCache('searchProspects', $this->request->response);

        return $this->request->response['data'];
    }

    public function loginAsUser($payload)
    {
        $this->addCache(
            'requires',
            $this->requires($payload, ['user_id', 'access_token', 'roles'])
        );

        $this->request->setPayload($payload);
        $this->request->addHeader(["X-ACCESS-TOKEN" => $this->client->getAccessToken()]);
        $this->addCache('loginAsUser', $this->request->response);
        $this->request->post('/admin/view_as');
        return $this->request->response['data'];
    }


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * TODO                                                                    *
     *  - redirects to multiple pages and goes to new dashboard                *
     *  - needs fixing                                                         *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * END OF FUNCTION THAT REQUIRE X-ACCESS-TOKEN in their header requests    *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


    public function getHeaders()
    {
       return $this->request->getHeaders(); 
    }

    public function ready()
    {
        return isset($this->ready);
    }

    public function inSession()
    {
        return isset($this->accessToken);
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCaches()
    {
        return $this->cache;
    }

    public function getCache($key = "")
    {
        if(!array_key_exists($key, $this->cache)) {
            return false;
        }

        return $this->cache[$key];
    }

    public function setServer($server)
    {
        $this->server = $server;
        $this->_server = $this->server . ':' . $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
        $this->_server = $this->server . ':' . $this->port;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getFullServer()
    {
        return $this->_server;
    }
}

