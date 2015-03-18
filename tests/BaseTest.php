<?php

class BaseTest extends PHPUnit_Framework_TestCase
{
	protected $client;

	public function __construct()
	{
		parent::__construct();
		$this->client = new Freedom_Client();
		//set access token here----------┐
		$this->client->setAccessToken('071545a7952a3074beabbfd39e3b1d5c3adb0bb028763e0d7b4f5ad01f903c8f');
	}


	public function testIncludes()
	{
    	$path = dirname(dirname(__FILE__)) . '/src/Freedom';
		$len = count(glob($path . "/*.php"));
		$counter = 0;
		foreach (glob($path . "/*.php") as $file) {
			$counter++;
			if ($counter < $len)
				$this->assertEquals(1, require_once($file));
    	}

    	$path = dirname(dirname(__FILE__)) . '/src/Freedom/Service';
		foreach (glob($path . "/*.php") as $file) {
			$this->assertEquals(1, require_once($file));
    	}
	}

	public function testAccessToken()
	{
		$this->assertTrue(null !== $this->client->getAccessToken(), 'access_token is missing');

		return $this->client;
	}
}