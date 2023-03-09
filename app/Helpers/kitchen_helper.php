<?php

if(!function_exists('touchFile')){
    function touchFile($fileName){
        
        if($fileName){

            $cmd = "touch $fileName"; 
            exec($cmd, $output);
            
        }
        
    }
}

if(!function_exists('removeExpiredAccessToken')){

    function removeExpiredAccessToken(){
        
        $path = publicFolder()."gmail_access_token/";
        $srch = 'ATKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);


        if(!empty($output)){
            
            foreach($output as $tmpObj){
                
                $fileNM = $tmpObj;

                if($fileNM != ''){
                    $rmvFl = $path.$fileNM;
                    exec("rm -f $rmvFl");
                }
            }
        }

    }
}


if(!function_exists('publicFolder')){
    function publicFolder(){
    
      //$documentRoot = $_SERVER["DOCUMENT_ROOT"];
      $documentRoot = $_SERVER["SCRIPT_FILENAME"];
      $documentRootArray = explode("/", $documentRoot);
      
      unset($documentRootArray[count($documentRootArray) - 1]);
      $documentRoot = implode("/", $documentRootArray);
      return $documentRoot."/";

    }
}

if(!function_exists('db_randomnumber')){
    function db_randomnumber(){

      $uniqNum = time().rand(10000,999999);

      return $uniqNum;

    }
}

if(!function_exists('create_local_folder')){
    function create_local_folder($path){

      if(!is_dir($path)){

        mkdir($path,0777);

        exec("chmod 777 $path");

      }

    }
}

if(!function_exists('fileWrite')){
    function fileWrite($file,$data,$mode='w+'){

      $fp = fopen($file,$mode);

      fwrite($fp,$data);

      fclose($fp);

    }
}

if(!function_exists('getPrevAccessToken')){
    function getPrevAccessToken(){
       
        $path = publicFolder()."gmail_access_token/";
        $srch = 'ATKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);

        $filePath = $output[0];
        $accssToknArr = explode("ATKN-", $filePath);
        $accessToken = $accssToknArr[1];
        return $accessToken;

    }
}

if(!function_exists('getPrevRefreshToken')){
    function getPrevRefreshToken(){
       
        $path = publicFolder()."gmail_access_token/";
        $srch = 'RTKN-*';
        exec('cd '.$path.' && find . -name "'.$srch.'"', $output);

        $filePath = $output[0];
        $rfrshToknArr = explode("RTKN-", $filePath);
        $refreshToken = $rfrshToknArr[1];
        $refreshToken = str_replace("#DK#", "/", $refreshToken);
        return $refreshToken;

    }
}




