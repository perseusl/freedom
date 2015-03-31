<?php

use AnyTV\Freedom\Client;

class BaseTest extends PHPUnit_Framework_TestCase
{
	protected $client;

	public function __construct()
	{
		$path = __DIR__ . '/data';

		foreach (glob($path . "/*.php") as $file) {
			$this->assertEquals(1, require_once($file));
		}

		parent::__construct();
		$this->client = new Client();
		$this->client->setAccessToken($this->testDataSet('ReturnAccessToken'));
	}

	public function testAuthenticate()
	{
		if (!$this->client->getAccessToken()) {
			$authenticatePayload = $this->testDataSet('AuthenticatePayload');
			$res = $this->client->authenticate($authenticatePayload);
			$this->assertArrayHasKey('user_data', $res);
			$this->assertArrayHasKey('scope_token', $res);
			$this->assertArrayHasKey('app_data', $res);
			$this->assertGreaterThanOrEqual(3, count($res['app_data']['roles']));
		}

		$this->assertTrue(null !== $this->client->getAccessToken(), 'access_token is missing');
	}

	public function testDataSet($request = null)
	{
		if( isset($request)) {
			$testData = new data_UserTest;
			$request[0] = strtolower($request[0]);

			return $testData->$request();
		}
	}
}
