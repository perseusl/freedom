<?php namespace AnyTV\Freedom\Service;

use AnyTV\Freedom\HttpRequest;
use AnyTV\Freedom\Client;

class Service {

	protected $adapter;
	protected $request;

    public function __construct(Client $client)
    {
		$this->adapter = $client;
		$this->request = new HttpRequest($this->adapter->config->getBasePath());
        $this->request->addHeader(
            array('X-ACCESS-TOKEN' => $this->adapter->getAccessToken())
        );
    }

    public function requires($data, $requires, $opts = [])
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
}
