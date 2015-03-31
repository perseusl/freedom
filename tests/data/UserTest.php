<?php


class data_UserTest
{
	//set $data['include'] to true to make the testUpdateUserInfo included in the test to be run
	public function returnAccessToken()
	{
		//set test access token here
		$accessToken = '2ef412e6c53c945e5c1f335758173c34f7803ec3d4d6ff23f13c1611abdccc23';
		return $accessToken;
	}
	
	public function authenticatePayload()
	{
		//set authentication payload here
		$payload = [];
		$payload['email'] = '';
		$payload['google_access_token'] = '';
		return $payload;
	}

	public function updateUserInfo()
	{
		$data = [];
		$data['include'] = false;
		$data['lname'] = '';
		$data['fname'] = '';
		$data['birthdate'] = '';
		$data['skype'] = '';
		$data['google_refresh_token'] = '';
		$data['street_address'] = '';
		$data['city'] = '';
		$data['state'] = '';
		$data['country'] = '';
		$data['postal_code'] = '';
		$data['avatar'] = '';
		$data['facebook'] = '';
		$data['twitter'] = '';
		$data['phone'] = '';

		return $data;
	}

	public function getRecruitsWithChannel()
	{
		$data = [];
		$data['include'] = true;
		$data['queryString'] = 'limit=5&page=1&required=&q=';

		return $data;
	}

	public function findProspect()
	{
		$data = [];
		$data['include'] = false;
		$data['searchKey'] = '';

		return $data;
	}

	public function addProspect()
	{
		$data = [];
		$data['include'] = false;

		$data['payload'] = array(
			'owner' => '',
			'thumbnail' => '',
			'username' => ''
			);

		return $data;
	}

	public function updateProspect()
	{
		$data = [];
		//status can be set as Contacted, Pitched, Demo, Negotiating, Closed (lost), Closed (won)
		$data['include'] = false;
		$data['prospect_id'] = '';
		$data['payload'] = array(
			'status' => '',
			'note' => ''
			 );

		return $data;
	}

	public function deleteProspects()
	{
		$data = [];
		$data['include'] = false;
		$data['ids'] = '';

		return $data;
	}
}