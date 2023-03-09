<?php 

$pageurl = site_url('dashboard?p=');
$queryString = "";
if(array_key_exists("p", $_GET)){
	$page = $_GET["p"]; //current page	
}else{
	$page = 0;
}

if($page == "" || $page == null || $page == 0){
	$page = 1;
}

include("header.php"); 
?>


<div class="mainContainer">
	
	<div class="accordion" id="accordionBox">
<?php
	
	if(!empty($messages)){

        
		$srNo = 1;
		foreach($messages as $tmpKy => $tmpRec){
				
			
			$id = $tmpRec["id"];
			$threadId = $tmpRec["threadId"];
			$messageId = $tmpRec["messageId"];
			$subject = $tmpRec["subject"];
			$fromEmail = $tmpRec["fromEmail"];
			$fromName = $tmpRec["fromName"];
			$recipients = $tmpRec["recipients"];
			$messageContentJson = $tmpRec["messageContent"];

			$messageContentArr = json_decode($messageContentJson, true);
			
			//echo "emailContentArr:<pre>"; print_r($emailContentArr); die;

			$messageContent = "<br>";
			//foreach($messageContentArr as $tmpEmailContentRw){
                
				$tmpCntDt = $messageContentArr["date"];
				$tmpCntSnpt = $messageContentArr["snippet"];
				$tmpCntMsg = $messageContentArr["message"];

				$messageContent = $messageContent."<b>Date:</b>".$tmpCntDt."<br><br><b>Snippet:</b>".$tmpCntSnpt."<br><br><b>Content:</b>".$tmpCntMsg."<br><br><br><br>";
			//}
			$attachments = $tmpRec["attachments"];
			$folderId = $tmpRec["folderId"];
			$dateTime = $tmpRec["dateTime"];
			$emailDateTime = $tmpRec["emailDateTime"];

		?>
			
			
  <div class="accordion-item">
    <h2 class="accordion-header" id="row_<?php echo $id; ?>">
      <button class="accordion-button <?php if($srNo > 1){echo "collapsed";}?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $id; ?>">
        <?php echo $subject; ?>&nbsp;<span class="SubjectDate"><?php echo date("M-d, Y", strtotime($emailDateTime)); ?></span>
      </button>
    </h2>
    <div id="collapse<?php echo $id; ?>" class="accordion-collapse collapse <?php if($srNo == 1){echo "show";}?>" aria-labelledby="row_<?php echo $id; ?>">
      <div class="accordion-body">     	
  		<div class="container">
		  <div class="row">
		    <div class="col">		    	
		    	<ul class="list-group">
  				<!--     <li class="list-group-item">
  						<span><b>Messages:</b> <a href="<?php //echo site_url("threaddetails/$threadId"); ?>" target="_blank">Click here to view messages</a></span>
  					</li> -->
  					<li class="list-group-item">
						<span><b>Subject:</b> <?php echo $subject; ?></span>
						</li>
  					<li class="list-group-item">
						<span><b>From Email:</b> <?php echo $fromEmail; ?></span>
						</li>
  					<li class="list-group-item">
						<span><b>From Name:</b> <?php echo $fromName; ?></span>
					</li>
  					<li class="list-group-item">
  						<span><b>Recipients:</b> <?php echo $recipients; ?></span>
  					</li>
  					<li class="list-group-item">
						<span><b>Message:</b> <?php echo $messageContent; ?></span>
					</li>
  					<li class="list-group-item">
						<span class="attachmentsMain"><b>Attachments:</b>
						<span class="attachmentsContainer">
						<?php //echo $attachments; 
							
							if($attachments != "" && $attachments != null){
								$attachmentsArr = explode(",", $attachments);
								if(!empty($attachmentsArr)){
									foreach ($attachmentsArr as $attchKey => $attchValue) {
										echo '<a href="'.base_url('user_assets/'.$folderId.'/'.$attchValue).'" target="_blank" download class="attachmentClass" >'.$attchValue.'</a>';
									}
								}	

							}

						?>
						</span>
						</span>
					</li>
					<!-- <li class="list-group-item">
						<span><b>Folder Id:</b> <?php echo $folderId; ?></span>
					</li> -->
					<li class="list-group-item">
						<span><b>Date:</b> <?php echo $dateTime; ?></span>
					</li>	
  				    <li class="list-group-item">
						<span><b>Email Date:</b> <?php echo $emailDateTime; ?></span>
					</li>
  				</ul>
		    </div>   
		  </div>
		</div>     
      </div>
    </div>
  </div>

 	<?php	
			$srNo++;
		}


	}else{
?>

	<div class="accordion-item">
    <h2 class="accordion-header" id="norow">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        No records found
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="norow" data-bs-parent="#accordionBox">
      <div class="accordion-body">
        No records found
      </div>
    </div>
  </div>

<?php
	
	}
?>
</div>

</div>


<?php include("footer.php"); ?>    