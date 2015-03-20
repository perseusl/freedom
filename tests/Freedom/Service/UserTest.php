<?php

class Freedom_Service_UserTest extends BaseTest
{
	//protected $client;
	protected $userDataHolder;
	protected $userService;
	protected $userId;

	public function __construct()
	{
		parent::__construct();
		parent::testAuthenticate();
	}

	public function testConnection()
	{
		$this->userService = new Freedom_Service_User($this->client);
		$this->userDataHolder = (object) $this->userService->getUser();
		$this->userId = $this->userDataHolder->_id;
	}

	public function testUserData()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$userData = $this->userDataHolder;
		$this->assertInternalType('object', $userData);
		//test for missing fields on user data
		$this->assertObjectHasAttribute('_id', $userData);
		$this->assertObjectHasAttribute('profile_info', $userData);
		$this->assertObjectHasAttribute('email', $userData);
		$this->assertObjectHasAttribute('app_data', $userData);
		$this->assertArrayHasKey('roles', $userData->app_data);
		$this->assertGreaterThanOrEqual(3, count($userData->app_data['roles']));
		$this->userDataHolder = $userData;

		$userInfo = json_decode($this->userService->getUserInfo()['data']);

		$this->assertObjectHasAttribute('_id', $userInfo);
		$this->assertObjectHasAttribute('profile_info', $userInfo);
		$this->assertObjectHasAttribute('email', $userInfo);
		$this->assertObjectHasAttribute('app_data', $userInfo);
		$this->assertObjectHasAttribute('roles', $userInfo->app_data);
		$this->assertGreaterThanOrEqual(3, count($userInfo->app_data->roles));

		

	}

	public function testGetRecruits()
	{
		if(!$this->userService) {
			$this->testConnection();
		}
		$userRecruits = json_decode($this->userService->getRecruits()['data']);

		if(isset($userRecruits->message)) {
			$this->assertEquals('User does not have any recruits', $userRecruits->message);
		} else {
			$this->assertInternalType('array', $userRecruits);

			foreach ($userRecruits as $recruit) {
				$this->assertObjectHasAttribute('_id', $recruit);
				$this->assertObjectHasAttribute('profile_info', $recruit);
				$this->assertObjectHasAttribute('email', $recruit);
				$this->assertObjectHasAttribute('app_data', $recruit);
				$this->assertGreaterThanOrEqual(3, count($recruit->app_data->roles));
				$this->assertTrue(null !== $recruit->app_data->referred_by, 'A recruited user has no referred by value set');
				$this->assertEquals($this->userId, $recruit->app_data->referred_by);
			}
		}
	}

	public function testUpdateUserInfo()
	{
		//
	}

	public function testGetRecruitsWithChannel()
	{
		if(!$this->userService) {
			$this->testConnection();
		}
		//change queryString on what you need/want
		$queryString = 'limit=5&page=1&required=&q=';
		$recruitsWithChannel = json_decode($this->userService->getRecruitsWithChannel($queryString)['data']);

		if(isset($recruitsWithChannel->message)) {
			$this->assertEquals('User does not have any recruits', $recruitsWithChannel);
		} else {
			$this->assertInternalType('array', $recruitsWithChannel);

			foreach ($recruitsWithChannel as $recruit) {
				$this->assertObjectHasAttribute('_id', $recruit);
				$this->assertObjectHasAttribute('profile_info', $recruit);
				$this->assertObjectHasAttribute('email', $recruit);
				$this->assertObjectHasAttribute('app_data', $recruit);
				$this->assertGreaterThanOrEqual(3, count($recruit->app_data->roles));
				$this->assertTrue(null !== $recruit->app_data->referred_by, 'A recruited user has no referred by value set');
				$this->assertEquals($this->userId, $recruit->app_data->referred_by);
			}
		}
	}

	public function testFindProspect()
	{
		//
	}

	public function testAddProspect()
	{

	}

	public function getUserProspect()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$prospects = $this->userService->getUserProspects();
		echo "======================================";
		var_dump($prospects);
		echo "+++++++++++++++++++++++++++++++++++++++++++";
	}
}