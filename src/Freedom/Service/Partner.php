<?php namespace AnyTV\Freedom\Service;

use \Exception;

class Partner extends Service {

	public function reauthChannel($rawpayload)
	{
		$pub = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);
		$priv = 'nHe2dcgZHS0RIAubTHL7UqjW6Z9hVTS0';
		$editedpayload = array();
		$editedpayload['access_token'] = $rawpayload['access_token'];
		$editedpayload['refresh_token'] = $rawpayload['refresh_token'];
		$editedpayload['hash'] = sha1($priv.':'.$pub);
		$editedpayload['pub'] = $pub;

		$payload = $this->adapter->requires($editedpayload, [
			'access_token', 'refresh_token', 'hash', 'pub'
		]);

		try {
			$this->request->setPayload($payload);
			$this->request->put('/channel/resolve/'.$rawpayload['channel_id']);

	        if (gettype($this->request->response['data']) === 'string') {
	            $this->request->response['data'] = json_decode($this->request->response['data'], true);
	        }

			if ($this->request->response['statusCode'] !== 200) {

				if ($this->request->response['data'] === null) {
					$this->request->response['data']['message'] = 'You don\'t have channel associated on the email. Try another one or create a channel on that email.';
				}
				throw new Exception ($this->request->response['data']['message']);
			}
		} catch (Exception $ex) {
            throw new Exception ($this->request->response['data']['message']);
        }
		return $this->request->response['data'];
	}

	public function addChannel($payload)
	{
		$payload = $this->adapter->requires($payload, [
			'network_id', 'channel_id', 'access_token', 'refresh_token'
			]);

		$this->request->setPayload($payload);
		$this->request->post('/channel');

        if (gettype($this->request->response['data']) === 'string') {
            $this->request->response['data'] = json_decode($this->request->response['data'], true);
        }

		if ($this->request->response['statusCode'] !== 200) {

			if ($this->request->response['data'] === null) {
				$this->request->response['data']['message'] = 'You don\'t have channel associated on the email. Try another one or create a channel on that email.';
			}

			throw new Exception ($this->request->response['data']['message']);
		}

		return $this->request->response['data'];
	}

	public function getChannels($ids, $part)
	{
		$query = $this->adapter->requires([
            'ids' => $ids,
            'part' => $part
        ], ['ids'], ['part']);

		$this->request->setQueryString($query);
		$this->request->get('/channels');

		return $this->request->response;
	}

	public function removeChannel($channel_id)
	{
		return 'Unavailable.';
	}

	public function getChannelAnalytics($analytics_id, $video_id)
	{
		$query = $this->adapter->requires([
			'analytics_id' => $analytics_id,
			'video_id' => $video_id
		], [
			 'analytics_id',
			 'video_id'
		]);

		$this->request->get('/channel/analytics/' . $query['analytics_id'] . '/video/' . $query['video_id']);

		return $this->request->response;	
	}

	public function getMusic()
	{
		$this->request->get('/music');

		return 'Feature Coming Soon.';
	}

	public function getChannelEarnings()
	{
		$this->request->get('/earnings/channels');

		return $this->request->response;
	}

	public function applyToNetwork($file)
	{
		$payload = $this->adapter->requires([
			'file' => $file
		], ['file']);

		$this->request->setPayload($payload);
		$this->request->post('/earnings/channel');
		
		return $this->request->response;
	}

	public function acceptNetworkContract($payload)
	{
		$payload = $this->adapter->requires($payload, [
			'name',
			'accept_email_message',
			'reject_email_message',
			'description',
			'tags',
			'banner',
			'username'
		]);

		$this->request->setPayload($payload);
		$this->request->post('/accept_network_contract');
		
		return $this->request->response;
	}

	public function getChannelRevenue($queryString)
	{
		$queryString = $this->adapter->requires($queryString, ['ids', 'part']);
		$this->request->setQueryString($queryString);
		$this->request->get('/channels');

        if (gettype($this->request->response['data']) === 'string') {
            $this->request->response['data'] = json_decode($this->request->response['data'], true);
        }

		if ($this->request->response['statusCode'] !== 200) {
			throw new Exception ($this->request->response);
		}

		return $this->request->response['data'];

	}
}
