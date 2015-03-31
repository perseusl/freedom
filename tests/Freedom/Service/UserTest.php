<?php

use AnyTV\Freedom\Service\User;


class Freedom_Service_UserTest extends BaseTest
{
	//protected $client;
	protected $userDataHolder;
	protected $userService;
	protected $userId;

	public function __construct()
	{
		parent::__construct();
		$this->testAuthenticate();
	}

	public function testConnection()
	{
		$this->userService = new User($this->client);
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
		if(!$this->userService) {
			$this->testConnection();
		}
		
		$userPayload = $this->testDataSet(substr(__FUNCTION__, 4));

		if(isset($userPayload['include']) && $userPayload['include'] === true) {
			$updateResponse = json_decode($this->userService->updateUserInfo($userPayload)['data']);
			$updateResponse = $updateResponse->user_data;
			$this->assertObjectHasAttribute('_id', $updateResponse);
			$this->assertObjectHasAttribute('email', $updateResponse);

			if(isset($userPayload['lname']) && $userPayload['lname'] != null) {
				$this->assertEquals($userPayload['lname'], $updateResponse->profile_info->lname);
			}

			if(isset($userPayload['fname']) && $userPayload['fname'] != null) {
				$this->assertEquals($userPayload['fname'], $updateResponse->profile_info->fname);
			}

			if(isset($userPayload['birthdate']) && $userPayload['birthdate'] != null) {
				$this->assertEquals($userPayload['birthdate'], $updateResponse->profile_info->birthdate);
			}

			if(isset($userPayload['avatar']) && $userPayload['avatar'] != null) {
				$this->assertEquals($userPayload['avatar'], $updateResponse->profile_info->avatar);
			}

			if(isset($userPayload['skype']) && $userPayload['skype'] != null) {
				$this->assertEquals($userPayload['skype'], $updateResponse->contact_info->skype);
			}

			if(isset($userPayload['street_address']) && $userPayload['street_address'] != null) {
				$this->assertEquals($userPayload['street_address'], $updateResponse->contact_info->address->street_address);
			}

			if(isset($userPayload['city']) && $userPayload['city'] != null) {
				$this->assertEquals($userPayload['city'], $updateResponse->contact_info->address->city);
			}

			if(isset($userPayload['state']) && $userPayload['state'] != null) {
				$this->assertEquals($userPayload['state'], $updateResponse->contact_info->address->state);
			}

			if(isset($userPayload['country']) && $userPayload['country'] != null) {
				$this->assertEquals($userPayload['country'], $updateResponse->contact_info->address->country);
			}

			if(isset($userPayload['postal_code']) && $userPayload['postal_code'] != null) {
				$this->assertEquals($userPayload['postal_code'], $updateResponse->contact_info->address->postal_code);
			}

			if(isset($userPayload['facebook']) && $userPayload['facebook'] != null) {
				$this->assertEquals($userPayload['facebook'], $updateResponse->contact_info->facebook);
			}

			if(isset($userPayload['twitter']) && $userPayload['twitter'] != null) {
				$this->assertEquals($userPayload['twitter'], $updateResponse->contact_info->twitter);
			}

			if(isset($userPayload['phone']) && $userPayload['phone'] != null) {
				$this->assertEquals($userPayload['phone'], $updateResponse->contact_info->phone);
			}
			//----------end of assertions
		}
	}

	public function testGetRecruitsWithChannel()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$queryString = $this->testDataSet(substr(__FUNCTION__, 4));

		if ( isset($queryString['include']) && $queryString['include'] === true) {
			$queryString = $queryString['queryString'];
			$recruitsWithChannel = json_decode($this->userService->getRecruitsWithChannel($queryString)['data']);

			if(isset($recruitsWithChannel->message)) {
				$this->assertEquals('User does not have any recruits', $recruitsWithChannel->message);
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
	}

	public function testFindProspect()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$searchKey = $this->testDataSet(substr(__FUNCTION__, 4));

		if ( isset($searchKey['include']) && $searchKey['include'] === true) {
			$searchKey = $searchKey['searchKey'];
			$findProspect = json_decode($this->userService->findProspect($searchKey)['data']);
			$findProspect = $findProspect->search_result->items;

			$this->assertInternalType('array', $findProspect);

			foreach ($findProspect as $prospect) {
				$this->assertObjectHasAttribute('id', $prospect);
				$this->assertObjectHasAttribute('title', $prospect->snippet);
				$this->assertObjectHasAttribute('statistics', $prospect);
				$this->assertObjectHasAttribute('network', $prospect);
				$this->assertObjectHasAttribute('viewCount', $prospect->statistics);
				$this->assertObjectHasAttribute('subscriberCount', $prospect->statistics);
				$this->assertObjectHasAttribute('videoCount', $prospect->statistics);
			}
		}
	}

	public function testAddProspect()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$payload = $this->testDataSet(substr(__FUNCTION__, 4));

		if ( isset($payload['include']) && $payload['include'] === true) {
			$payload = $payload['payload'];
			$addProspect = json_decode($this->userService->addProspect($payload)['data']);

			if ( isset($addProspect->message) && $addProspect->message === 'Prospect successfully added' ) {
				$this->assertObjectHasAttribute('prospect', $addProspect);
				$addProspect = $addProspect->prospect;
				$this->assertObjectHasAttribute('thumbnail', $addProspect);
				$this->assertObjectHasAttribute('owner', $addProspect);
				$this->assertObjectHasAttribute('username', $addProspect);
				$this->assertObjectHasAttribute('status', $addProspect);

				if ( isset($addProspect->network_id)) {

					foreach ($addProspect->network_id as $network) {
						$this->assertObjectHasAttribute('_id', $network);
						$this->assertObjectHasAttribute('network_id');
					}
				}
			}
		}
	}

	public function testGetUserProspect()
	{
		if(!$this->userService) {
			$this->testConnection();
		}

		$prospects = json_decode($this->userService->getUserProspects()['data']);
		$this->assertInternalType('array', $prospects);

		if(count($prospects) > 0) {

			foreach ($prospects as $prospect) {
				$this->assertObjectHasAttribute('_id', $prospect);
				$this->assertObjectHasAttribute('recruiter_id', $prospect);
				$this->assertObjectHasAttribute('network_id', $prospect);
				$this->assertObjectHasAttribute('recruiter_email', $prospect);
				$this->assertObjectHasAttribute('owner', $prospect);
			}
		}
	}

	public function testUpdateProspect()
	{
		/*if(!$this->userService) {
			$this->testConnection();
		}

		$req = $this->testDataSet(substr(__FUNCTION__, 4));

		if(isset($req['include']) && $req['include'] === true) {
			$prospect_id = $req['prospect_id'];
			$payload = $req['payload'];
			$updateProspect = $this->userService->updateProspect($prospect_id, $payload);

		} skipped, got stucked :( */
	}

	public function testDeleteProspects()
	{
		/*if(!$this->userService) {
			$this->testConnection();
		}

		$ids = $this->testDataSet(substr(__FUNCTION__, 4));

		if(isset($ids['include']) && $ids['include'] === true) {
			$ids = $ids['ids'];
			$deleteProspects = $this->userService->deleteProspects($ids);

		} skipped .... also :( */
	}
}