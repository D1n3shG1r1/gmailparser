<?php
namespace App\Libraries;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Class GMailApi{

	function listUserMessages($AccessToken, $userId){

		exec("curl 'https://gmail.googleapis.com/gmail/v1/users/$userId/messages' --header 'Authorization: Bearer $AccessToken' --header 'Accept: application/json'", $output);

		
		$outputStr = implode("",$output);
		$result =  json_decode($outputStr, true);
		
		echo "<pre>"; print_r($result); die;
		return $output;
		
	} 	


	function getThread($AccessToken, $userId, $threadId){

		
		exec("curl 'https://gmail.googleapis.com/gmail/v1/users/$userId/threads/$threadId' --header 'Authorization: Bearer $AccessToken' --header 'Accept: application/json'", $output);

		$outputStr = implode("",$output);
		$result =  json_decode($outputStr, true);
		

		if(array_key_exists("error", $result)){

			$response = array(
				"C" => 101,
				"R" => $result
			);
		
		}else{
			$response = array(
				"C" => 100,
				"R" => $result
			);			
		}

		
		return json_encode($response); 

	}

	function getMessage($AccessToken, $userId, $messageId){
		
		exec("curl 'https://gmail.googleapis.com/gmail/v1/users/$userId/messages/$messageId' --header 'Authorization: Bearer $AccessToken' --header 'Accept: application/json'", $output);

		
		$outputStr = implode("",$output);
		$result =  json_decode($outputStr, true);
		
		echo "<pre>"; print_r($result); die;
		return $output;
	}

	function getAttachment($AccessToken, $userId, $messageId, $attachmentId){
		
  		exec("curl 'https://gmail.googleapis.com/gmail/v1/users/$userId/messages/$messageId/attachments/$attachmentId' --header 'Authorization: Bearer $AccessToken' --header 'Accept: application/json'", $output);

		$outputStr = implode("",$output);
		$result =  json_decode($outputStr, true);
		

		if(array_key_exists("error", $result)){

			$response = array(
				"C" => 101,
				"R" => $result
			);
		
		}else{
			$response = array(
				"C" => 100,
				"R" => $result
			);			
		}
		
		return json_encode($response); 

	}


}
?>