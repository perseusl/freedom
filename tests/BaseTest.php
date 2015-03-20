<?php

class BaseTest extends PHPUnit_Framework_TestCase
{
	protected $client;

	public function __construct()
	{
		parent::__construct();
		$this->client = new Freedom_Client();
		//set access token here----------┐ or set email and google_access_token @ line 18 :)
		$this->client->setAccessToken('071545a7952a3074beabbfd39e3b1d5c3adb0bb028763e0d7b4f5ad01f903c8f');
	}

	public function testAuthenticate()
	{
		if (!$this->client->getAccessToken()) {
			$res = $this->client->authenticate(['email' => 'laguador.p@gmail.com', 'google_access_token' => 'ya29.OwH55-hszyywSZ7eLLZSA6oD7-lCht4MkFoasTPcsYXxgHyQDJN8PixNO5SYL-uRvOHA1LLVdJvbdQ']);
			$this->assertArrayHasKey('user_data', $res);
			$this->assertArrayHasKey('scope_token', $res);
			$this->assertArrayHasKey('app_data', $res);
			$this->assertGreaterThanOrEqual(3, count($res['app_data']['roles']));
		}

		$this->assertTrue(null !== $this->client->getAccessToken(), 'access_token is missing');
	}
}