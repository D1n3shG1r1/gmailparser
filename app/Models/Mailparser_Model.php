<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mailparser_Model extends Model
{
    
    //protected $table = 'visual_parsed_emails';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        
    }

    function saveEmailData($threadData, $messagesData){
        
        $threadId = $threadData["threadId"];
        
        //if thread exist
        $sql = "SELECT `id` FROM `gmail_threads` WHERE `threadId` = '$threadId'";
        $query =  $this->db->query($sql);
        $threads = $query->getResultArray(); 
        
        //echo $sql."<pre>"; print_r($threads); die;

        $threadRecordId = 0;
        $messageRecordId = 0;
        if(!empty($threads)){
            //already exist
            $threadExist = 1;
            $threadRecordId = $threads[0]["id"];
        }else{
            //not exist
            $threadExist = 0;
        
            //$builder = $this->db->table('visual_parsed_emails');
            $builder = $this->db->table('gmail_threads');
            $builder->insert($threadData);

            if($this->db->affectedRows() > 0){
                $threadRecordId = $threadData["id"];
            }else{
                $threadRecordId = 0;
            }
        } 
        
        if($threadRecordId > 0){
            //save thread messages
            foreach($messagesData as $msgObj){
                
                $messageId = $msgObj["messageId"];
                $threadId = $msgObj["threadId"];
                
                /*
                $msgObj["emailDateTime"];
                $msgObj["recipients"];
                $msgObj["fromEmail"];
                $msgObj["fromName"];
                $msgObj["subject"];
                $msgObj["messageContent"];
                $msgObj["folderId"];
                $msgObj["attachments"];
                */

                //if thread exist
                $sql = "SELECT `id` FROM `gmail_messages` WHERE `messageId` = '$messageId' AND `threadId` = '$threadId'";
                $query =  $this->db->query($sql);
                $messagesResult = $query->getResultArray();

                if(!empty($messagesResult)){
                    //already exist
                    $messageRecordId = $messagesResult[0]["id"];
                }else{
                    //insert
                

                    $builder = $this->db->table('gmail_messages');
                    $builder->insert($msgObj);

                    if($this->db->affectedRows() > 0){
                        $messageRecordId = $msgObj["id"];
                    }

                }


            }
        }


        $result = array("threadRecordId" => $threadRecordId, "messageRecordId" => $messageRecordId);

        //echo "<pre>"; print_r($result); die;

        return $result;

    }


    function getThreadMessageIds($threadId){

        //check for thread
        $sql = "SELECT `threadId` FROM `gmail_threads` WHERE `threadId` = '$threadId'";
        $query = $this->db->query($sql);
        $thread = $query->getRowArray();
        $resultThreadId = "";
        $messages = array();
        $folderId = 0;

        if(!empty($thread)){
            
            
            $resultThreadId = $thread["threadId"];

            //check for thread messages

            $sql = "SELECT `messageId`, `folderId` FROM `gmail_messages` WHERE `threadId` = '$threadId'";
            $query = $this->db->query($sql);
            $messagesResult = $query->getResultArray(); 
            if(!empty($messagesResult)){
                foreach($messagesResult as $msgRow){
                    $messages[] = $msgRow["messageId"];
                    $folderId = $msgRow["folderId"];
                }
            }
        }


        $result = array(
            "threadId" => $resultThreadId,
            "messages" => $messages,
            "folderId" => $folderId
        );

        return $result;

    }


    function getParsedEmails($page, $from, $to, $offset, $perPage){
      
        $whereClause = "";
        if(($from && $from != "") &&  ($to && $to != "")){
            $whereClause = " WHERE `emailDateTime` >= '$from' AND `emailDateTime` <= '$to'";
        }
        
        $limit = $perPage;


        //$sql = "SELECT COUNT('id') AS 'TotalRecords' FROM `visual_parsed_emails` $whereClause";
        $sql = "SELECT COUNT('id') AS 'TotalRecords' FROM `gmail_threads` $whereClause";
        
        $query =  $this->db->query($sql);
        if($whereClause != ""){
            $totalRecordsResult = $query->getResultArray(); 
            $totalRows  = $totalRecordsResult[0]["TotalRecords"];
        }else{
            $totalRecordsResult = $query->getRowArray();    
            $totalRows  = $totalRecordsResult["TotalRecords"]; 
        }
        
        

        //$sql = "SELECT * FROM `visual_parsed_emails`  $whereClause LIMIT $limit OFFSET $offset";
        $sql = "SELECT * FROM `gmail_threads` $whereClause ORDER BY `emailDateTime` DESC LIMIT $limit OFFSET $offset";
        $query =  $this->db->query($sql);
        $threads = $query->getResultArray();
        
        /*
        if(!empty($threads)){
            $tmpThreadIdsArr = array();
            foreach($threads as $thread){
                $tmpThreadIdsArr[] = $thread["threadId"];
            }

            if(!empty($tmpThreadIdsArr)){

             $sql = "SELECT * FROM `gmail_messages` WHERE `threadId` IN()";
             $query =  $this->db->query($sql);
             $threads = $query->getResultArray();
            }

        }*/

        
        $results = array();
        $results["TotalRecords"] = $totalRows;
        $results["emails"] = $threads;
        
        return $results;
        
    }

    function getThreadMessages($threadId){
        $sql = "SELECT * FROM `gmail_messages` WHERE `threadId` = '$threadId' ORDER BY `emailDateTime` DESC";
        $query =  $this->db->query($sql);
        $messages = $query->getResultArray();

        return $messages;
    }

    function login($username, $password){

        $sql = "SELECT `id` FROM `visual_whatsapp_admin_users` WHERE `username` = '$username' AND `password` = '$password'";
        
        $query =  $this->db->query($sql);
        $result = $query->getRowArray();
        
        if(!empty($result)){
            return $result["id"];
        }else{
            return 0;
        }
        
    }

}