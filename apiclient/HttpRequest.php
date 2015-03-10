<?php namespace Freedom\Adapter;

class Curl {
    private $ch;
    private $querystring = array();
    private $headers = array();
    private $payload = array();
    private $server = '';
    private $path = ''; 
    private $file;
    public $response = array();

    /**
     * @param string $server FQDN only
     */
    function __construct ($server) {
        $this->server = $server;
        $this->ch = curl_init($server);
        $this->file = fopen(storage_path() . '/logs/err.txt', 'w');
    }

    /**
     * @param string $file file is a file path  
     */
    public function useSSL ($file = null) {
        if (isset($file) && file_exists($file)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($this->ch, CURLOPT_CAINFO, $file);
        }

        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * @param array $arr expected to be an array
     * @return bool
     */
    public function setQueryString ($arr = array()) {
        if (is_array($arr)) {
            $this->querystring = $arr;
            return true;
        }

        return false;
    }

    /**
     * @param array $arr expected to be an array
     * @return bool
     */
    public function setPayload ($arr = array()) {
        if (is_array($arr)) {
            $this->payload = $arr;
            return true;
        }

        return false;
    }

    /**
     * @param array $var key value pair
     * @return array returns the new headers 
     * */
    public function addHeader ($var = array()) {
        if (!is_array($var)) {
            return false;
        }

        foreach($var as $key => $value) {
            $this->headers[$key] = $value;
        }

        return $this->headers;
    }

    /**
     * @param array $var value array list
     * @return array returns the new headers
     * */
    public function removeHeader ($var = array()) {
        switch(gettype($var)){
            case 'string': 
                unset($this->headers[$var]);
                break;
            case 'array':
                if (count($z) < 1) {
                    return false;
                }

                foreach ($var as $key) {
                    unset($this->headers[$key]);
                }

                break;
            default:
                return false;
        }

        return $this->headers;
    }

    public function clearHeader () {
        $this->headers = array();
    }

    private function setHeader () {
        $headers = array();
        foreach($this->headers as $key => $value) {
            array_push($headers, "{$key}: {$value}");
        }

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    public function getHeaders () {
        $headers = array();
        foreach($this->headers as $key => $value) {
            array_push($headers, "{$key}: {$value}");
        }

        return $headers;
    }

    public function getQueryString () {
        return $this->querystring;
    }

    public function getPayload () {
        return $this->payload;
    }

    public function followLocation () {
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
    }

    public function unfollowLocation () {
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, false);
    }

    public function get ($path) {
        $this->response = array();
        $qs = '';

        if (isset($this->querystring)) {
            $qs = '?' . http_build_query($this->querystring);
        }

        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_STDERR, $this->file);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_URL, $this->server . $path . $qs);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setHeader();

        $this->response['data'] = trim(curl_exec($this->ch));
        $this->response['statusCode'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->response['info'] = curl_getinfo($this->ch);

        curl_reset($this->ch);
        $this->querystring = null;
        $this->ch = curl_init($this->server);
    }

    public function post ($path) {
        $this->response = array();

        if ($this->payload) {
            $payload = http_build_query($this->payload);
        }

        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_STDERR, $this->file);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_URL, $this->server . $path);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);


        $this->setHeader();
        $this->response['data'] = trim(curl_exec($this->ch));
        $this->response['statusCode'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->response['info'] = curl_getinfo($this->ch);

        curl_reset($this->ch);
        $this->payload = null;
        $this->ch = curl_init($this->server);
    }

    public function put ($path) {
        $this->response = array();

        if ($this->payload) {
            $payload = http_build_query($this->payload);
        }

        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_STDERR, $this->file);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_URL, $this->server . $path);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setHeader();

        $this->response['data'] = trim(curl_exec($this->ch));
        $this->response['statusCode'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->response['info'] = curl_getinfo($this->ch);

        curl_reset($this->ch);
        $this->payload = null;
        $this->ch = curl_init($this->server);
    }

    public function delete ($path) {
        $this->response = array();
        $qs = '';

        if (isset($this->querystring)) {
            $qs = '?' . http_build_query($this->querystring);
        }

        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_STDERR, $this->file);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->ch, CURLOPT_URL, $this->server . $path . $qs);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setHeader();

        $this->response['data'] = trim(curl_exec($this->ch));
        $this->response['statusCode'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->response['info'] = curl_getinfo($this->ch);

        curl_reset($this->ch);
        $this->querystring = null;
        $this->ch = curl_init($this->server);
    }
}
?>
