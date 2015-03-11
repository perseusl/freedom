<?php

class Freedom_Service_Network {
	
	protected $request;
	protected $adapter;

	public function __construct($client)
	{
		$this->adapter = new Freedom_Adapter($client);
		$this->request = $this->adapter->getRequest(); //request instance
	}

	public function getNetworkInfo($network_id)
	{
		$network_id = $this->adapter->requires(['network_id' => $network_id ], ['network_id']);

		$this->request->get('/network/' . $network_id['network_id']);
		
		if ($this->request->response['statusCode'] !== 200) {
			throw new \Exception($this->request->response['data']);
		}
		
		if(gettype($this->request->response['data']) === 'string') {
            		$this->request->response['data'] = json_decode($this->request->response['data']);		
		}
		
		return $this->request->response['data'];
	}

	public function updateNetworkInfo($network_id, $payload)
	{
		$network_id = $this->adapter->requires(['network_id' => $network_id ], ['network_id']);
		$payload = $this->adapter->requires( $payload, [],  [
            'logo',
            'name',
            'banner',
            'power_lines',
            'email',
            'description',
            'landing_page_styles',
            'links',
            'tags',
            'accept_email_message',
            'reject_email_message',
            'owner_id'
			]);

		if ( gettype($payload) === 'string' ) {

            $message = [
                'message' => $payload,
                'statusCode' => 500
            ];

            return $message;
        }

		$this->request->setPayload($payload);
		$this->request->post('/network/' . $network_id['network_id']);

		return $this->request->response;
	}

	public function viewChannelApplicants($query)
	{
		$query = $this->adapter->requires( $query, ['network_id'], [
			'page',
			'limit',
			'status',
			'q'
		]);
		$this->request->setQueryString($query);
		$this->request->get('/applicants/channel');
		return $this->request->response;
	}

	public function changeApplicantsStatus($payload)
	{
		$payload = $this->adapter->requires( $payload, [
			'id',
			'status'
		]);
		$this->request->setPayload($payload);
		$this->request->put('/applicant/channel');
		return $this->request->response;
	}

	public function getNetworkChannels($query)
	{
		$$query = $this->adapter->requires( $$query, ['network_id'], [
			'page',
			'limit'
		]);
		$this->request->setQueryString($query);
		$this->request->get('/network/channels');
		return $this->request->response;
	}

	public function getUserInfo($user_id)
	{
		$query = $this->adapter->requires(['user_id' => $user_id ], ['user_id']);
		$this->request->get('/user/' . $query['user_id']);
		return $this->request->response;
	}

	public function getChannelAnalytics($channel_id)
	{
		$query = $this->adapter->requires(['channel_id' => $channel_id ], ['channel_id']);
	
		$this->request->get('/channel/' . $query['channel_id']);
		return $this->request->response;
	}

	public function requestRevenueChange($payload)
	{
		$payload = $this->adapter->requires($payload, [
			'channel_id',
			'rev_share',
			'date_effective',
			'network_id'
		], ['comments']);
		
		$this->request->setPayload($payload);
		$this->request->post('/apply/revshare');
		return $this->request->response;
	}

	public function unpartnerChannel($channel_id)
	{
		return 'Feature Coming Soon';
	}

	public function getRecruiterApplicants()
	{
		$this->request->get('/applicants/recruiter');
	
		return $this->request->response;
	} 

	public function changeRecruiterStatus($recruiter_id, $payload)
	{
		$recruiter_id = $this->adapter->requires(['recruiter_id' => $recruiter_id ], ['recruiter_id']);
		$payload = $this->adapter->requires( $payload, ['status']);
		$this->request->setPayload($payload);
		$this->request->put('/applicant/recruiter/'. $recruiter_id['recruiter_id']);
		return $this->request->response;
	}

	public function searchChannelApplicant($payload)
	{
		$payload = $this->adapter->requires( $payload, ['search']);
		$this->request->setQueryString($payload);
		$this->request->get('/network/channel_search_name');
		return $this->request->response;
	}

	public function getRecruitersLeaderboard()
	{
		$this->request->get('/network/recruited_by_recruiters');
		return $this->request->response;
	}

	public function getRecruitersProspects()
	{
		$this->request->get('/network/recruiter/prospects');
		return $this->request->response;
	}

	public function applyAsSponsor($payload)
	{
		$payload = $this->adapter->requires( $payload, [
			'network_sponsor_id',
			'network_id'
		]);
		$this->request->setPayload($payload);
		$this->request->post('/network/sponsored');
		return $this->request->response;
	}
	
	public function getSponsoredList($sponsor_id, $query)
	{
		$sponsor_id = $this->adapter->requires(['sponsor_id' => $sponsor_id ], ['sponsor_id']);
		$query = $this->adapter->requires( $query, [], [
			'page',
			'filter',
			'sort'
		]);
		$this->adapter->setQueryString($query);
		$this->request->get('/network/sponsored/' . $sponsor_id['sponsor_id']);
		
		return $this->request->response;
	}
	
	public function changeSponsorStatus($payload)
	{
		$payload = $this->adapter->requires( $payload, [
			'sponsorship_id',
			'status'
		]);
		$this->request->setPayload($payload);
		$this->request->post('/network/accept_sponsor');
		return $this->request->response;
	}
	
	public function getSponsorRevenue($sponsor_id)
	{
		$sponsor_id = $this->adapter->requires(['sponsor_id' => $sponsor_id ], ['sponsor_id']);
		$this->request->get('/sponsored/' . $sponsor_id['sponsor_id'] . '/revenue');
		
		return $this->request->response;
	}

	public function getNetworkEarnings()
	{
		$this->request->get('/earnings/network');
		return $this->request->response();
	}

	public function getRecruiters($query)
	{
		$this->request->setQueryString($query);
		$this->request->get('/recruits');
		return $this->request->response;
	}
}
