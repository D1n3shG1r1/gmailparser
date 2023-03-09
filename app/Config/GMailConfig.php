<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GMailConfig extends BaseConfig
{
	


	public $api_key = "AIzaSyAoHnT2dfmBIDJ-OurNOIToQsH71O0Ic5I";
	public $client_id = "768439717706-0qvbopa7bdes1ds7r15splvqdelbdp77.apps.googleusercontent.com";
	public $client_secret = "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";
	public $scope = "https://mail.google.com/";
	//public $redirect_uri = "localhost/verifyauth";
	public $redirect_uri = "verifyauth";
	public $offline = true;

	public function getApiKeys(){
		
		$keysData = array(
			"api_key" => $this->api_key,
			"client_id" => $this->client_id,
			"client_secret" => $this->client_secret,
			"scope" => $this->scope,
			"redirect_uri" => $this->redirect_uri,
			"offline" => $this->offline
		);

		return $keysData;

	}
}
