<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\GMailConfig;

//$request = \Config\Services::request();
use App\Libraries\GMailApi; // Import library
use App\Libraries\GoogleClient; // Import library
use App\Models\Mailparser_Model; // Load Model


class Mailparser extends Controller
{
	
	public $api_key = null;
	public $client_id = null;
	public $client_secret = null;
	public $scope = null;
	public $redirect_uri = null;
	public $offline = null;    
	public $Mailparser_Model = null;
	public $GMA = null;
	public $GMC = null;
	public $GC = null;
	public $session = null;	

	function __construct(){

		$this->session = \Config\Services::session();
        $this->session->start();

		helper("kitchen");

		$this->GMC = new GMailConfig();
		$keysData = $this->GMC->getApiKeys();

		$this->api_key = $keysData["api_key"];
		$this->client_id = $keysData["client_id"];
		$this->client_secret = $keysData["client_secret"];
		$this->scope = $keysData["scope"];
		$this->redirect_uri = site_url($keysData["redirect_uri"]);
		$this->offline = $keysData["offline"];

		//library objects
		$this->GC = new GoogleClient();
		$this->GMA = new GMailApi();

		//model objects
		$this->Mailparser_Model = new Mailparser_Model();

		
		
	}
	
    function index(){
        echo "Access Denied"; die;
    }

    public function test(){
    	die("access denied.");
    	echo "hello";
		/*
		$threadId = "18320acc0c7498fa";
    	$ThreadExistingData = $this->Mailparser_Model->getThreadMessageIds($threadId);

    	echo "<pre>";
    	print_r($ThreadExistingData);
		die;
		*/
    	$this->getUserMessageData("gfdg", "gdfg", array());
    	//$this->getParsedEmails();
    	die;

    			
    	
    	$GMA = new GMailApi();
    	
    	
    	$accessToken = getPrevAccessToken();
    	$userId = "upkit.dineshgiri@gmail.com";
    	$threadId = "18345bbacaa00880";
    	$threadId = "18345bad1993a713";
    	//$GMA->listUserMessages($accessToken, $userId);
    	
    	$threadJson = $GMA->getThread($accessToken, $userId, $threadId);
    	$resp = json_decode($threadJson, true);
    	echo "resp:<pre>"; print_r($resp);
    	die;

    	$messageId = "18331d157b9b12c3";
    	$attachmentId = "ANGjdJ8_b8PRlShNtse6AoFj0EOC1ijdqWEN7PDH5NXWl2VI1GPk4oBMzqPhsCL0qh4HqmQ9FcFoiCCLsLymQpdh-A3vBBcf0WqXoencG8UmCNzqT7N5mwaQe9Mcs1TVhqya2V1E6XDgiJQSkHRqwe7ef9wXzylrbjuR8XjuJ5zvk46PrfzDd3YUHhUFsBU4egBFyF-hEz2OMupmbIUWYfxCkGRisk8-Wmj0_Q0W4y5ojATJHUKpfjmrZwJ4GqEADTTUVU1XQ3U2Fa3DlQqjyQdCDumWw1vlRnlWbOBg8HdGg3UKHyIa2fhD0eO-ogE";
    	
    	$folderId = db_randomnumber();
    	$dirPath = publicFolder()."user_assets/".$folderId."/";
		create_local_folder($dirPath);

    	$attchJson = $GMA->getAttachment($accessToken, $userId, $messageId, $attachmentId);

		$attchResp = json_decode($attchJson, true);
		
		$C = $attchResp["C"];
    	$R = $attchResp["R"];

    	if($C == 100){

    		$base64 = $R["data"];

    		//$decodedContent = base64_decode($base64, true);
    		//$decodedContent = base64_decode($base64);
    		//$fileData = $decodedContent;
    		$fileData = $base64;
    		$fileName = "demo.xlsx";
    		$file = $dirPath.$fileName;
			fileWrite($file,$fileData);
			echo "done";

    	}else{
    		//write log
    	}

    	
    }

    function saveToScipForm(){

    	//$client_id , $client_secret_key, $refresh_token

    	$refresh_token = getPrevRefreshToken();
    	$access_token = getPrevAccessToken();
    	$client_id = $this->client_id;
    	$client_secret_key = $this->client_secret;
		$okReport = 0;
		if(!$this->GC->oauth_check_token($access_token)){

			$access_token = $this->GC->oauth_renew_access_token($client_id , $client_secret_key, $refresh_token);
			if($access_token){
				//save access token
				removeExpiredAccessToken();
				$tmpAccessToken = $access_token;
				$path = publicFolder()."gmail_access_token";
				$filePath = $path."/ATKN-".$tmpAccessToken;
				touchFile($filePath);
				$okReport = 1;
			}else{
				//error
				//show error and auto close the window
				$okReport = 0;
			}
				
		}else{
			$okReport = 1;			
		}
		
		
		if($okReport > 0){
			$userData = $this->session->get("userData");
			$data = array();
    		$data["page_title"] = "Save To SCIP";
    		$data["userData"] = $userData;
    		return view("emailform", $data);	
		}else{
			$data = array();
    		$data["page_title"] = "Error";
    		$data["message"] = "Session Expired";
    		return view("forbidden", $data);	
		}
	
    }

    function saveUserMessage(){

    	if($this->request->isAJAX()){
		 	
		 	$userEmail = $this->request->getPost("userEmail");
			$threadId =  $this->request->getPost("threadId");
			$SCIP_name =  $this->request->getPost("SCIP_name");
			$SCIP_from =  $this->request->getPost("SCIP_from");
			$SCIP_dealname =  $this->request->getPost("SCIP_dealname");
			$SCIP_dealtype =  $this->request->getPost("SCIP_dealtype");
			$SCIP_source =  $this->request->getPost("SCIP_source");
			$SCIP_action =  $this->request->getPost("SCIP_action");
			$SCIP_note =  $this->request->getPost("SCIP_note");
			$SCIP_industryTags =  $this->request->getPost("SCIP_industryTags");
			$SCIP_technologyTags =  $this->request->getPost("SCIP_technologyTags");
			$SCIP_revenueModelTags =  $this->request->getPost("SCIP_revenueModelTags");
			
			$formData = array(
				"SCIP_name" => $SCIP_name,
				"SCIP_from" => $SCIP_from,
				"SCIP_dealname" => $SCIP_dealname,  
				"SCIP_dealtype" => $SCIP_dealtype,
				"SCIP_source" => $SCIP_source,
				"SCIP_action" => $SCIP_action,
				"SCIP_note" => $SCIP_note,
				"SCIP_industryTags" => $SCIP_industryTags,
				"SCIP_technologyTags" => $SCIP_technologyTags,
				"SCIP_revenueModelTags" => $SCIP_revenueModelTags
			);
			
			$saved = $this->getUserMessageData($userEmail, $threadId, $formData);
			
			if($saved > 0){
				if($saved == 2){
					$result = array("C" => 102, "R" => "success", "M" => "success");
				}else{
					$result = array("C" => 100, "R" => "success", "M" => "success");	
				}
	    		
	    	}else{
				$result = array("C" => 101, "R" => "error", "M" => "error");
	    	}
	    	
	    	echo json_encode($result); die;


		}else{

			$data = array();
    		$data["page_title"] = "Error";
    		$data["message"] = "Access Denied!";
    		return view("forbidden", $data);
		}

    }

    function getUserMessageData($userEmail, $threadId, $formData = array()){
    	
    	//----- get thread
    	$userId = $userEmail;
    	//$userId = "upkit.dineshgiri@gmail.com";
    	//$threadId = "18320acc0c7498fa";
    	//$threadId = "18345bbacaa00880";
    	//$threadId = "18345bad1993a713";
    	
		$folderId = db_randomnumber();
			
    	$accessToken = getPrevAccessToken();
    	
    	$threadJson = $this->GMA->getThread($accessToken, $userId, $threadId);

    
    	$threadResp = json_decode($threadJson, true);


    	//----- get messages from thread
    	//echo "<pre>"; print_r($threadResp); echo "</pre>"; die;
    	$C = $threadResp["C"];
    	$R = $threadResp["R"];

    	$saveData = array();
    	
    	if($C == 100){

    		$id = $R["id"]; //thread Id
    		$messages = $R["messages"]; //messages


    		// check for previous entries
    		$ThreadExistingData = $this->Mailparser_Model->getThreadMessageIds($id);
    		if(!empty($ThreadExistingData)){
    			
    			$extThrdId = $ThreadExistingData["threadId"];
    			$extMsgIds = $ThreadExistingData["messages"];
    			$folderId = $ThreadExistingData["folderId"];
    			
    			if($folderId == 0 || $folderId == null || $folderId == ""){
    				$folderId = db_randomnumber();
    			}

    			if($extThrdId != "" && $extThrdId == $id){

    				// thread is already exist


    				if(!empty($extMsgIds)){
    					// remove existing messages from array
    					
    					foreach($messages as $key => $tmp_Msg_Obj){
    						$tmp_Msg_Id = $tmp_Msg_Obj["id"];
    						if(in_array($tmp_Msg_Id, $extMsgIds)){

    							unset($messages[$key]);

    						}
    					}
    					
    				}

    			}	


    		}

    		
    		$saveData["threadId"] = $id;
    		$saveData["messages"] = array();
    		
    		if(!empty($messages)){
    			
    			// save messages to db
    		
	    		foreach($messages as $tmpMsgObj){
	    			
	    			$tempMessgArr = array();
	    			$tempMessgContentArr = array();
	    			$tempMessgAttchArr = array();

	    			$MSG_id = $tmpMsgObj["id"];
		    		$MSG_threadId = $tmpMsgObj["threadId"];
		    		$MSG_labelIds = $tmpMsgObj["labelIds"];
		    		$MSG_snippet = $tmpMsgObj["snippet"];
		    		$MSG_payload_partId = $tmpMsgObj["payload"]["partId"];
		    		$MSG_payload_mimeType = $tmpMsgObj["payload"]["mimeType"];
		    		$MSG_payload_filename = $tmpMsgObj["payload"]["filename"];
		    		$MSG_payload_headers = $tmpMsgObj["payload"]["headers"];
		    		$MSG_payload_parts = $tmpMsgObj["payload"]["parts"];
		    		

		    		$tempMessgArr[$MSG_id] = array();
	    			$tempMessgContentArr[$MSG_id] = array();
	    			$tempMessgAttchArr[$MSG_id] = array();

					$tempMessgArr[$MSG_id]["snippet"] = $MSG_snippet;
		    		//--- push headers like "Content-Type","Date","Subject","From","To","Authentication-Results"
		    		
		    		$reqHdrs = array("Content-Type","Date","Subject","From","To","Authentication-Results");
		    		foreach($MSG_payload_headers as $MSG_payload_headers_obj){
		    			$tmpNm = $MSG_payload_headers_obj["name"];
		    			$tmpVl = $MSG_payload_headers_obj["value"];
		    			
		    			if(in_array($tmpNm, $reqHdrs)){
		    				
		    				if(strtolower($tmpNm) == "authentication-results"){

		    					$tmpVlParts = explode("smtp.mailfrom=", $tmpVl);
		    					$fromEmailArr = explode(";", $tmpVlParts[1]);
		    					$fromEmail = trim($fromEmailArr[0]);
		    					$tempMessgArr[$MSG_id]["FromEmail"] = $fromEmail;
		    				}else{
		    					$tempMessgArr[$MSG_id][$tmpNm] = $tmpVl;
		    				}

		    			} 
		    			
		    			$tempMessgArr[$MSG_id]["threadId"] = $MSG_threadId;
		    			$tempMessgArr[$MSG_id]["id"] = $MSG_id;
		    		}

		    		//--- get attachments of message if any
		    		foreach($MSG_payload_parts as $MSG_payload_parts_obj){
		    			
		    			$tmp_partId = $MSG_payload_parts_obj["partId"];
		    			$tmp_mimeType = $MSG_payload_parts_obj["mimeType"];
		    			$tmp_filename = $MSG_payload_parts_obj["filename"];
		    			$tmp_body = $MSG_payload_parts_obj["body"];

		    			//echo "tmp_partId:$tmp_partId ,tmp_mimeType:$tmp_mimeType ,tmp_filename:$tmp_filename<br>";

		    			if($tmp_filename != "" && $tmp_filename != null){
		    				//---- get attachment
		    				$tmp_attchId = $tmp_body["attachmentId"];

		    				$messageId = $MSG_id;
		    				$attachmentId = $tmp_attchId;
		    				
		    				//$attchJson = $GMA->getAttachment($accessToken, $userId, $messageId, $attachmentId);

		    				$attchJson = $this->GMA->getAttachment($accessToken, $userId, $messageId, $attachmentId);

		    				$attchResp = json_decode($attchJson, true);
		    				
							$C = $attchResp["C"];
					    	$R = $attchResp["R"];

					    	if($C == 100){
					    		$bs64 = $R["data"];
					    		$tempMessgAttchArr[$MSG_id][] = array( 
					    									"fileName" => $tmp_filename,
					    									"mimeType" => $tmp_mimeType,
					    									"base64Data" => $bs64
					    								);

					    	}else{
					    		//write log
					    	}

		    			}else{
		    				//not an attachment
		    				if(strtolower($tmp_mimeType) == "text/plain"){
		    					//get content from parts

		    					//if(strtolower($tmp_mimeType) == "text/plain" && $tmp_filename == ""){
		    						$msgTxtContent = $tmp_body["data"];
		    						$tempMessgContentArr[$MSG_id][] = $msgTxtContent;
		    					//}
			    				
		    				}else if(strtolower($tmp_mimeType) == "multipart/alternative"){
		    					//get content from sub-parts of parts
		    					$tmp_subparts = $MSG_payload_parts_obj["parts"];
		    				
			    				foreach($tmp_subparts as $tmpSubPartObj){
			    					$tmpSbPrtMimeType = $tmpSubPartObj["mimeType"];
			    					$tmpSbPrtFlNm = $tmpSubPartObj["filename"];
			    					$tmpSbPrtBdy = $tmpSubPartObj["body"];

			    					if(strtolower($tmpSbPrtMimeType) == "text/plain" && $tmpSbPrtFlNm == ""){
			    						$msgTxtContent = $tmpSbPrtBdy["data"];
			    						$tempMessgContentArr[$MSG_id][] = $msgTxtContent;
			    					}
			    				}
		    				}

		    			}

		    		}


		    		//==== collaborate all the assets of message
		    		
		    		
		    		
		    		if(array_key_exists("FromEmail", $tempMessgArr[$MSG_id]) == false){
		    			$tempMessgArr[$MSG_id]["FromEmail"] = "";	
		    		} 

		    		$tmpCollabData = array();
		    		

		    		$saveData["messages"][] = array(
									    			"details" => $tempMessgArr[$MSG_id],
									    			"base64content" => $tempMessgContentArr[$MSG_id][0],
									    			"attachments" => $tempMessgAttchArr[$MSG_id]
									    		);

		    	}
			}
    		
    		
	    	//--- write attachments in folder
	    	
			$saveDataThreadId = $saveData["threadId"];
			$saveDataMessages = $saveData["messages"];

			
			if(!empty($saveDataMessages)){

		    	$dirPath = publicFolder()."user_assets/".$folderId."/";
				create_local_folder($dirPath);

				$FinalSaveData = array();
				$tmpEmailContentArr = array();
		    	$tmpEmailAttchArr = array();

		    	//echo "<pre>"; print_r($saveDataMessages); die;

		    	foreach($saveDataMessages as $ky => $saveDataObj){
		    		
		    			
		    		$SD_details = $saveDataObj["details"];	
		    		$SD_base64content = $saveDataObj["base64content"];	
		    		

		    		$SD_base64content = str_replace("_","/",$SD_base64content);
		    		$SD_base64content = str_replace("-","+",$SD_base64content);
		    		$tmpMessageContent = base64_decode($SD_base64content);

		    		$tmpMsgId = $SD_details["id"];
		    		$SD_snippet = $SD_details["snippet"];
		    		$tmpEmailContentArr[$tmpMsgId] = array(
		    												"id" => $tmpMsgId,
		    												"threadId" => $saveDataThreadId,
		    												"date" => $SD_details["Date"],
		    												"emailDateTime" => date("Y-m-d H:i:s", strtotime($SD_details["Date"])),
		    												"recipients" => $SD_details["To"],
		    												"fromEmail" => $SD_details["FromEmail"],
		    												"fromName" => $SD_details["From"],
		    												"subject" => $SD_details["Subject"],
		    												"snippet" => $SD_snippet,
		    												"message" => $tmpMessageContent,
		    												"folderId" => $folderId,
		    												"attachments" => array()

		    										);

		    		if($ky == 0){
		    
		    			//$FinalSaveData[""] = $SD_details["id"];
			    		$FinalSaveData["recipients"] = $SD_details["To"];
			    		$FinalSaveData["fromEmail"] = $SD_details["FromEmail"];
			    		$FinalSaveData["fromName"] = $SD_details["From"];
			    		$FinalSaveData["emailDateTime"] = date("Y-m-d H:i:s", strtotime($SD_details["Date"]));
			    		$FinalSaveData["subject"] = $SD_details["Subject"];
			    		
					}
					
					
		    		$SD_base64Content = $saveDataObj["base64content"];
		    		$SD_attachments = $saveDataObj["attachments"];
		    		if(!empty($SD_attachments)){

		    			foreach($SD_attachments as $tmpAttchObj){
		    				$tmpFname = $tmpAttchObj["fileName"];
		    				$tmpBase64 = $tmpAttchObj["base64Data"];
		    				$tmpBase64 = str_replace("_","/",$tmpBase64);
		    				$tmpBase64 = str_replace("-","+",$tmpBase64);
		    				$fileData = base64_decode($tmpBase64);

		    				$file = $dirPath.$tmpFname;
							fileWrite($file,$fileData);		
							
							$tmpEmailAttchArr[$tmpMsgId] = $tmpFname;
							$tmpEmailContentArr[$tmpMsgId]["attachments"][] = $tmpFname;
							//echo $file."<br>$tmpBase64<br><br>";

		    			}

		    		}
		    	}
		
		    	
				$messagesSaveDataArr = array();
		    	foreach($tmpEmailContentArr as $msgIdKy => $tmpEmailCont){
		    		
		    		// save messages of thread
		    		
		    		$messageSaveData = array();
		    		$messageSaveData["id"] = db_randomnumber();
		    		$messageSaveData["messageId"] = $tmpEmailCont["id"];
		    		$messageSaveData["threadId"] = $tmpEmailCont["threadId"];
		    		//$messageSaveData[""] = $tmpEmailCont["date"];
		    		$messageSaveData["emailDateTime"] = $tmpEmailCont["emailDateTime"];
		    		$messageSaveData["recipients"] = $tmpEmailCont["recipients"];
		    		$messageSaveData["fromEmail"] = $tmpEmailCont["fromEmail"];
		    		$messageSaveData["fromName"] = $tmpEmailCont["fromName"];
		    		$messageSaveData["subject"] = $tmpEmailCont["subject"];
		    		$messageSaveData["messageContent"] = json_encode(array(
		    											"date" => $tmpEmailCont["date"],
		    											"snippet" => $tmpEmailCont["snippet"],
		    											"message" => $tmpEmailCont["message"]
		    										));
		    		$messageSaveData["folderId"] = $tmpEmailCont["folderId"];
		    		$messageSaveData["attachments"] = implode(",", $tmpEmailCont["attachments"]);

		    		$messagesSaveDataArr[] = $messageSaveData;
		    		//print_r($messageSaveData);
		    	}



		    	//die;
				/*$formData = array();
				$formData["SCIP_name"] = "";
				$formData["SCIP_from"] = "";
				$formData["SCIP_dealname"] = ""; 
				$formData["SCIP_dealtype"] = "";
				$formData["SCIP_source"] = "";
				$formData["SCIP_action"] = "";
				$formData["SCIP_note"] = "";
				$formData["SCIP_industryTags"] = "";
				$formData["SCIP_technologyTags"] = "";
				$formData["SCIP_revenueModelTags"] = "";*/

				$SCIP_name = $formData["SCIP_name"];
				$SCIP_from = $formData["SCIP_from"];
				$SCIP_dealname = $formData["SCIP_dealname"];  
				$SCIP_dealtype = $formData["SCIP_dealtype"];
				$SCIP_source = $formData["SCIP_source"];
				$SCIP_action = $formData["SCIP_action"];
				$SCIP_note = $formData["SCIP_note"];
				$SCIP_industryTags = $formData["SCIP_industryTags"];
				$SCIP_technologyTags = $formData["SCIP_technologyTags"];
				$SCIP_revenueModelTags = $formData["SCIP_revenueModelTags"];
			

				$FinalSaveData["id"] = db_randomnumber();
				$FinalSaveData["name"] = $SCIP_name;
		    	$FinalSaveData["notes"] = $SCIP_note;
		    	$FinalSaveData["dealName"] = $SCIP_dealname;
		    	$FinalSaveData["dealType"] = $SCIP_dealtype;
		    	$FinalSaveData["source"] = $SCIP_source;
		    	$FinalSaveData["action"] = $SCIP_action;
		    	$FinalSaveData["industries"] = $SCIP_industryTags;
		    	$FinalSaveData["technologies"] = $SCIP_technologyTags;
				$FinalSaveData["revenueModels"] = $SCIP_revenueModelTags;
				$FinalSaveData["threadId"] = $id;
				$FinalSaveData["dateTime"] = date("Y-m-d H:i:s");
				
				//$FinalSaveData["folderId"] = $folderId;
		    	//$FinalSaveData["emailContent"] = json_encode($tmpEmailContentArr);
		    	//$FinalSaveData["attachments"] = implode(",",$tmpEmailAttchArr);		
		    	
				//echo "FinalSaveData:<pre>"; print_r($FinalSaveData); die;
		    		
		    	$saved = $this->Mailparser_Model->saveEmailData($FinalSaveData, $messagesSaveDataArr);
		    }else{
		    	//Alreday saved no data to save
		    	$saved = 2;
		    }

    	}else{
    		//write log
    		$saved = 0;
    	}

    	//echo "saved:".$saved; die;	
    	return $saved; 
    	
    }


    function getParsedEmails(){
    	

    	$loginId = $this->session->get("loginId");
        if(!$loginId || $loginId == null || $loginId == 0){
            return redirect()->to(site_url("signin"));
        }

    	$from = false;
    	$to = false;	
    	
    	if($this->request->getGet('p')){
    		$page = $this->request->getGet('p');	
    	}else{
    		$page = 1;
    	}
    	

    	if($this->request->getGet('from')){
    		$from = $this->request->getGet('from');
    		//if (DateTime::createFromFormat('Y-m-d', $from) !== false) {
			  	$from = date('Y-m-d', strtotime($from));
				$from = $from." 00:00:00";
			//}
    	}
    	

    	if($this->request->getGet('to')){
    		$to = $this->request->getGet('to');
    		//if (DateTime::createFromFormat('Y-m-d', $to) !== false) {
			  	$to = date('Y-m-d', strtotime($to));
				$to = $to." 23:59:59";
			//}
    	}else{
    		$to = date('Y-m-d');
    		$to = $to." 23:59:59";
    	}
    		
    	
    	
    	$offset = 0;
    	$perPage = 10;	
    	if($page > 1){
    		$tmpOffset = $page * $perPage;
			$offset = $tmpOffset - 1;
			$offset = $offset - $perPage;
			$offset = $offset + 1;
		}

		//echo $page."--". $from."--".$to."--".$offset."--".$perPage; die;

    	$records = $this->Mailparser_Model->getParsedEmails($page, $from, $to, $offset, $perPage);

    	$data = array();
    	$data["page_title"] = "Dashboard";
    	$data["TotalRecords"] = $records["TotalRecords"];
    	$data["records"] = $records["emails"];

    	//echo "<pre>"; print_r($data); die;
		return view("dashboard", $data);
    	
    }

    function threaddetails($threadId){
    	
    	if($threadId){

    		$messages = $this->Mailparser_Model->getThreadMessages($threadId);

	    	$data = array();
	    	$data["page_title"] = "Messages";
	    	$data["messages"] = $messages;

	    	//echo "data:<pre>"; print_r($data);
	    	return view("messages", $data);

    	}else{

    		$data = array();
    		$data["page_title"] = "Error";
    		$data["message"] = "Access Denied!";
    		return view("forbidden", $data);
    	}
    }

    function signin(){
    	
 		if($this->request->getPost('submit')){

    		$username = $this->request->getPost('username');
    		$password = $this->request->getPost('password');

    		//set login session and redirect to dashboard
    		$userId = $this->Mailparser_Model->login($username, $password);
    		if($userId > 0){
    			$this->session->set("loginId", $userId);		
    			return redirect()->to(site_url("dashboard"));
    		}else{
    			$data = array();
	    		$data["page_title"] = "Sign In";
	    		$data["invalidCredentials"] = 1;
	    		return view("signin", $data);		
    		}
    		
    	}else{
    		$data = array();
	    	$data["page_title"] = "Sign In";
	    	$data["invalidCredentials"] = 0;
	    	return view("signin", $data);	
    	}
    	
    }

    function signout(){
    	
    	//destroy session and redirect to signin
    	$this->session->destroy();
		return redirect()->to(site_url("signin"));

    }

   
}
?>
