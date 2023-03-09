<?php

namespace App\Controllers;
use Config\GMailConfig;
use App\Libraries\GoogleClient; // Import library


class Authenticate extends BaseController
{
	
	public $api_key = null;
	public $client_id = null;
	public $client_secret = null;
	public $scope = null;
	public $redirect_uri = null;
	public $offline = null;    
	public $session = null;	

	function __construct(){

		$this->session = \Config\Services::session();
        $this->session->start();

		helper("kitchen");
		
		$GMC = new GMailConfig();
		$keysData = $GMC->getApiKeys();

		$this->api_key = $keysData["api_key"];
		$this->client_id = $keysData["client_id"];
		$this->client_secret = $keysData["client_secret"];
		$this->scope = $keysData["scope"];
		$this->redirect_uri = site_url($keysData["redirect_uri"]);
		$this->offline = $keysData["offline"];
		
	}

	
    function index(){
        $data = array();
		$data["page_title"] = "Error";
		$data["message"] = "Access Denied!";
		return view("forbidden", $data);
    }

    function auth(){
    
		$p = $this->request->getGet("p");
		if($p && $p != "" && $p != null){
			$p = urldecode($p);
			$p = str_replace(" ","+",$p);
		}
		
		$userData = array('p' => $p);

        $this->session->set("userData", $userData);

        $gauth = $this->session->get("gauth");
        
        if(!$gauth || $gauth == 0 || $gauth == null){
        	//go for auth



      	$api_key = $this->api_key;
		$client_id = $this->client_id;
		$client_secret = $this->client_secret;
		$scope = $this->scope;
		$redirect_uri = $this->redirect_uri;
		$offline = $this->offline;
		
		$state = null;
        
        $GC = new GoogleClient();

		$oauthResp = $GC->oauth_request_handler($client_id, $client_secret, $scope,
			$offline, $redirect_uri, $state);

		if(!empty($oauthResp)){

			$action = $oauthResp["action"];
			$actionData = $oauthResp["data"];

			if($action == "redirect"){
				//ok
				
				$this->session->set("gauth", 1);
				
				$result = array(
					"C" => 100,
					"R" => array("authUrl" => $actionData),
					"M" => "success" 
				);

			}else{
				//error

				$result = array(
					"C" => 101,
					"R" => array("authUrl" => $actionData),
					"M" => "error" 
				);

			}
			

		}else{

			//error

			$result = array(
				"C" => 102,
				"R" => array("authUrl" => "No response from api"),
				"M" => "error" 
			);
		}


		//echo json_encode($result); die;
		//echo "<pre>"; print_r($result); die;


		if($result["C"] == 100){
			$authUrl = $result["R"]["authUrl"];
			
			return redirect()->to($authUrl);
		}else{
			//error
			$data = array();
    		$data["page_title"] = "Error";
    		$data["message"] = "Authentication Failed!";
    		return view("forbidden", $data);
		}
	}else{


		
		return redirect()->to(site_url("savetoscip"));

	}


    }


    function verify(){
    	
		$api_key = $this->api_key;
		$client_id = $this->client_id;
		$client_secret = $this->client_secret;
		$scope = $this->scope;
		$redirect_uri = $this->redirect_uri;
		$offline = $this->offline;

		if(!empty($_GET)){

			$code = $_GET["code"];
			//$scope = $_GET["scope"];


			$GC = new GoogleClient();

			$accessTokenResponse = $GC->getAccssTokn($client_id, $client_secret, $scope,$redirect_uri, $offline, $code);

			$action = $accessTokenResponse["action"];
			$data = $accessTokenResponse["data"];
			
			if($action == "done"){
				
				//save access & refresh token
				
				$tmpAccessToken = $data["access_token"];
				$tmpRefreshToken = $data["refresh_token"];
				
				//$email = "upkit.dineshgiri@gmail.com";
				//$path = publicFolder()."gmail_access_token/$email";
				//create_local_folder($path);
				
				removeExpiredAccessToken();
				$tm =time();
				$path = publicFolder()."gmail_access_token";
				$filePath = $path."/ATKN-".$tmpAccessToken;
				touchFile($filePath);

				$tmpRefreshToken = str_replace("/", "#DK#",$tmpRefreshToken);
				$filePath = $path."/RTKN-".$tmpRefreshToken;
				touchFile($filePath);
				
				return redirect()->to(site_url("savetoscip"));	

			}else{
				
				$data = array();
	    		$data["page_title"] = "Error";
	    		$data["message"] = "Authentication Failed!";
	    		return view("forbidden", $data);
			}

		}else{
			$data = array();
    		$data["page_title"] = "Error";
    		$data["message"] = "Access Denied!";
    		return view("forbidden", $data);
		}

		

    }
}
?>
