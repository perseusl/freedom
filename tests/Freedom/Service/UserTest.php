<?php

class Freedom_Service_UserTest extends BaseTest
{
	protected $client;
	public function __construct()
	{
		parent::__construct();
		$this->client = parent::testAccessToken();
	}

	public function testUserData()
	{
		$userService = new Freedom_Service_User($this->client);
		$userData = (object) $userService->getUser();
		$this->assertInternalType('object', $userData);
		//test for missing fields on user data
		$this->assertObjectHasAttribute('_id', $userData);
		$this->assertObjectHasAttribute('profile_info', $userData);
		$this->assertObjectHasAttribute('email', $userData);
		$this->assertObjectHasAttribute('app_data', $userData);
		$this->assertArrayHasKey('roles', $userData->app_data);
		$this->assertGreaterThanOrEqual(3, count($userData->app_data['roles']));

		$userInfo = json_decode($userService->getUserInfo()['data']);
		$this->assertObjectHasAttribute('_id', $userInfo);
		$this->assertObjectHasAttribute('profile_info', $userInfo);
		$this->assertObjectHasAttribute('email', $userInfo);
		$this->assertObjectHasAttribute('app_data', $userInfo);
		$this->assertObjectHasAttribute('roles', $userInfo->app_data);
		$this->assertGreaterThanOrEqual(3, count($userInfo->app_data->roles));

		$userRecruits = json_decode($userService->getRecruits()['data']);
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
			}
		}

	}
}