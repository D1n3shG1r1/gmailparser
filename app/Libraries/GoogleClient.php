<?php
namespace App\Libraries;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ROOTPATH . 'vendor/autoload.php'; 


Class GoogleClient{
	
	public function reset() {
		
		$this->client_id = false;
		$this->client_secret_key = false;
		$this->access_token = false;
	
		
	}

	public function init($access_token, $refresh_token, $client_id, $client_secret_key) {
		$this->reset();

		if (!$this->oauth_check_token($access_token) && false == ($access_token = $this->oauth_renew_access_token($client_id , $client_secret_key, $refresh_token)) ) {
			return 'ACCESS_TOKEN ERROR';
		}

		$this->client_id = $client_id;
		$this->client_secret_key = $client_secret_key;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;

		return true;
	}


	public function oauth_request_handler($client_id, $client_secret_key, $scope, $offline = true, $redirect = NULL, $state = NULL) {
		


		$output = array('action' => 'none');
		$client = new \Google\Client();
		
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret_key);
		// https://developers.google.com/sheets/guides/authorizing#OAuth2Authorizing
		$client->setScopes($scope);
		if ($offline){
			$client->setAccessType('offline');
		}
		$client->setPrompt('select_account consent');
		if (empty($redirect))
			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		$redirect = filter_var($redirect, FILTER_SANITIZE_URL);
		$client->setRedirectUri($redirect);
		if (!empty($state)) {
			$output['state'] = $state;
			if (!is_array($state))
				$state = http_build_query($state);
			$client->setState($state);
		}
	
		try{
			if (isset($_GET['code'])) {
				$client->authenticate($_GET['code']);
				if (!empty($client->getAccessToken())) {
					$output['action'] = 'done';
					$output['data'] = $client->getAccessToken();
					return $output;
				}
			}
		} catch (Exception $e) {
		}

		if (!isset($_GET['error'])) {
			$output['action'] = 'redirect';
			$output['data'] = $client->createAuthUrl();
		} else {
			$output['action'] = 'error';
			$output['data'] = $_GET['error'];
		}
		return $output;
	}


	function getAccssTokn($client_id, $client_secret_key, $scope,$redirect,$offline = false, $code){

		$output = array('action' => 'none');
		$client = new \Google\Client();

		$client->setClientId($client_id);
		$client->setClientSecret($client_secret_key);
		// https://developers.google.com/sheets/guides/authorizing#OAuth2Authorizing
		$client->setScopes($scope);
		if($offline){
			$client->setAccessType('offline');
		}
		//$client->setPrompt('select_account consent');

		$redirect = filter_var($redirect, FILTER_SANITIZE_URL);
		$client->setRedirectUri($redirect);
		$client->authenticate($code);


		if ($client->isAccessTokenExpired()) {
		
	        // Refresh the token if possible, else fetch a new one.
	        //$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        
	        //$resp = $client->getAccessToken();
	        $resp = $client->getRefreshToken();

			if (!empty($resp)) {
				$output['stage'] = 1;
				$output['action'] = 'done';
				$output['data'] = $client->getAccessToken();
				
			}else{
				$output['stage'] = 1;
				$output['action'] = 'none';
				$output['data'] = 'no access token';
			}

	    }else{


			$resp = $client->getAccessToken();

			if (!empty($resp)) {
				$output['stage'] = 2;
				$output['action'] = 'done';
				$output['data'] = $client->getAccessToken();
				
			}else{
				$output['stage'] = 2;
				$output['action'] = 'none';
				$output['data'] = 'no access token';
			}
		}

		return $output;
	}

	function oauth_check_token($token, $min_expire_time = 600) {
		$ret = @json_decode(@file_get_contents(
			'https://www.googleapis.com/oauth2/v1/tokeninfo' . '?' . http_build_query( array(
				'access_token' => $token
			))
		));
		return isset($ret->expires_in) && $ret->expires_in > $min_expire_time;
	}

	function oauth_renew_access_token($client_id, $client_secret_key, $refresh_token) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
			'refresh_token' => $refresh_token,
			'client_id' => $client_id,
			'client_secret' => $client_secret_key,
			'grant_type' => 'refresh_token',
		))); 
		$ret = @json_decode(curl_exec($ch));
		curl_close($ch);
		if (isset($ret->access_token))
			return $ret->access_token;
		return false;
	}


}

?>