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


<style>
.recordsContainer
{
	padding: 0 2rem;
}
.recordsContainer li
{
	padding: 1rem;
	box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
	border-top: 5px solid orangered;
	border-radius: 10px;
	margin-bottom: 1.5rem;
}

.recordList{
	list-style: none;
	padding: 10px;
}

#row_head{
	background-color: #ccc;
	list-style: none;
    border-bottom: 1px solid #eee;
    font-size: 30px;
    font-weight: normal;
    padding: 10px;

}

.recordList .recordHead, .recordList .recordDetails{
	width: 100%;
    float: left;
}
.recordListWrap
{
	display: flex;
	flex-wrap: wrap;
}
.recordListWrap span
{
	flex: 0 0 50%;
	max-width: 50%;
	display: block;
	padding: 5px 0;
}
.recordList .recordHead
{
	float: none;
	margin: 0 0 10px 0;
}
.recordList ul
{
	box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
	padding: 1.5rem;
}

.recordList h4
{
	border-bottom: 1px solid #ddd;
	padding-bottom: 15px;
}

.attachmentClass{
	border: 1px solid #ccc;
    width: auto;
    margin-bottom: 5px;
    padding: 6px !important;
    border-radius: 3px;
    box-shadow: #cce !important;
    background-color: #CCE;
    margin-left: 5px;
    font-size: 11px;
    display: inline-block;

}

.tagClass{
	border: 1px solid #ccc;
    width: auto;
    float: left;
    padding: 6px !important;
    border-radius: 3px;
    box-shadow: #cce !important;
    background-color: #CCE;
    margin-left: 5px;
    font-size: 11px;
}

.pagination{
		text-align: right;
    padding-right: 32px;
   	float: right;
   	margin-top: 10px
}

.pagination .pagesno{
	border: 1px solid #ccc;
    padding: 0 6px;
    background-color: #ccc;
    border-radius: 3px;
    color: #000;
    text-decoration: none;
    margin-left: 10px;
}

.pagination .pagesno.selected{
	border: 1px solid #ccc;
    padding: 0 6px;
    background-color: #CCE;
    border-radius: 3px;
    color: #000;
    text-decoration: none;
}
.attachmentsContainer
{
	display: inline-block;
    vertical-align: top;
    max-height: 60px;
    width: 80%;
    overflow-y: auto;
}
.btn
{
	display: inline-block;
	font-weight: 400;
	color: #212529;
	text-align: center;
	vertical-align: middle;
	background-color: transparent;
	border: 1px solid transparent;
	padding: 10px 20px;
	font-size: 1rem;
	line-height: 1.5;
	border-radius: 0.25rem;
	box-shadow: none;
	text-decoration: none;
	cursor: pointer;
	transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.btn-primary
{
	color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}
.sender-form .btn
	{	
		background: #198754;
		border-color: #198754;
		margin-top: 19px;

	}
.set-input
{
	position: relative;
}
.recordListHead
{
	overflow: hidden;
}	
.recordListHead a
{
	float: right;
}	
.recordListHead a .btn
{
	padding: 5px 15px;
	border-color: #dc3545;
	background: #dc3545;
}	
.row
	{
		display: flex;
		flex-wrap: wrap;
	}
	
	.sender-form
	{
		padding: 0 2rem;
	}

	.sender-form .row
	{	
	    
    box-shadow: 0 10px 25px -5px rgb(0 0 0 / 20%);
    border-top: 5px solid orangered;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    padding: 1.5rem;
	}
	.sender-form .row div
	{
		flex: 0 0 20%;
		max-width: 20%;
		padding: 10px 15px;
	}
	.sender-form input[type="date"]
	{
		width: 100%;
		display: inline-block;
		height: 48px;
		background: #fff;
		border-radius: 5px;
		border: 1px solid #ddd;
		padding: 15px;
		outline: none;
	}

	.SubjectDate{margin-left: 10px; font-weight: 500;}
	.accordion-button{font-weight: 500;}

</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>


<div class="mainContainer">
<div class="accordion" id="accordionBox">
<?php
	
	if(!empty($records)){


		$srNo = 1;
		foreach($records as $tmpKy => $tmpRec){
				
			
			$id = $tmpRec["id"];
			$name = $tmpRec["name"];
			$notes = $tmpRec["notes"];
			$dealName = $tmpRec["dealName"];
			$dealType = $tmpRec["dealType"];
			$source = $tmpRec["source"];
			$action = $tmpRec["action"];
			$industries = $tmpRec["industries"];
			$technologies = $tmpRec["technologies"];
			$revenueModels = $tmpRec["revenueModels"];
			$subject = $tmpRec["subject"];
			$fromEmail = $tmpRec["fromEmail"];
			$fromName = $tmpRec["fromName"];
			$recipients = $tmpRec["recipients"];
			$emailDateTime = $tmpRec["emailDateTime"];
			$threadId = $tmpRec["threadId"];
			$dateTime = $tmpRec["dateTime"];

			/*
			$emailContentJson = $tmpRec["emailContent"];
			$emailContentArr = json_decode($emailContentJson, true);
			
			//echo "emailContentArr:<pre>"; print_r($emailContentArr); die;

			$emailContent = "<br>";
			foreach($emailContentArr as $tmpEmailContentRw){

				$tmpCntDt = $tmpEmailContentRw["date"];
				$tmpCntSnpt = $tmpEmailContentRw["snippet"];
				$tmpCntMsg = $tmpEmailContentRw["message"];

				$emailContent = $emailContent."<b>Date:</b>".$tmpCntDt."<br><br><b>Snippet:</b>".$tmpCntSnpt."<br><br><b>Content:</b>".$tmpCntMsg."<br><br><br><br>";
			}

			$attachments = $tmpRec["attachments"];
			$folderId = $tmpRec["folderId"];
			*/

			$industriesArr = explode(",", $industries);
			$technologiesArr = explode(",", $technologies);
			$revenueModelsArr = explode(",", $revenueModels);

		?>
			
			
  <div class="accordion-item">
    <h2 class="accordion-header" id="row_<?php echo $id; ?>">
      <button class="accordion-button <?php if($srNo > 1){echo "collapsed";}?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $id; ?>">
        <?php echo $subject; ?><span class="SubjectDate"><?php echo date("M-d, Y", strtotime($emailDateTime)); ?></span>
      </button>
    </h2>
    <div id="collapse<?php echo $id; ?>" class="accordion-collapse collapse <?php if($srNo == 1){echo "show";}?>" aria-labelledby="row_<?php echo $id; ?>">
      <div class="accordion-body">

      	

  		<div class="container">
		  <div class="row">
		    <div class="col">
		    	
		    	<ul class="list-group">
  					<!---
  					<li class="list-group-item">
  						<span><b>Sr No:</b> <?php //echo $srNo; ?></span>
  					</li>
  					--->
  					
  					<!---
  					<li class="list-group-item">
						<span><b>ID:</b> <?php //echo $id; ?></span>
						</li>
						--->
  					
  					<li class="list-group-item">
						<span><b>Name:</b> <?php echo $name; ?></span>
						</li>
  					
  					<li class="list-group-item">
						<span><b>Notes:</b> <?php echo $notes; ?></span>
						</li>


  					<li class="list-group-item">
						<span><b>Deal Name:</b> <?php echo $dealName; ?></span>
						</li>
  					
						<li class="list-group-item">
						<span><b>Deal Type:</b> <?php echo $dealType; ?></span>
						</li>
  					<li class="list-group-item">
						<span><b>Source:</b> <?php echo $source; ?></span>
						</li>
  					<!---
  					<li class="list-group-item">
						<span class="attachmentsMain"><b>Attachments:</b>
						<span class="attachmentsContainer">
						<?php //echo $attachments; 
							/*
							if($attachments != "" && $attachments != null){
								$attachmentsArr = explode(",", $attachments);
								if(!empty($attachmentsArr)){
									foreach ($attachmentsArr as $attchKey => $attchValue) {
										echo '<a href="'.base_url('user_assets/'.$folderId.'/'.$attchValue).'" target="_blank" download class="attachmentClass" >'.$attchValue.'</a>';
									}
								}	

							}*/

						?>
						</span>
						</span>
						</li>
						--->
  					<li class="list-group-item">
						<span><b>Action:</b> <?php echo $action; ?></span>
						</li>
  					<!---
  					<li class="list-group-item">
						<span><b>Folder Id:</b> <?php //echo $folderId; ?></span>
						</li>
						--->
  					<li class="list-group-item">
						<span><span style="float:left;"><b>Industries:</b></span>

						<?php 
							if(!empty($industriesArr)){
								foreach($industriesArr as $indstTg){
									echo '<span class="tagClass">'.$indstTg.'</span>';
								}
							}
						?>
						</span>
						</li>
  					<li class="list-group-item">
						<span><span style="float:left;"><b>Technologies:</b></span>
						<?php 
							if(!empty($technologiesArr)){
								foreach($technologiesArr as $techTg){
									echo '<span class="tagClass">'.$techTg.'</span>';
								}
							}
						?>
						</span>
						</li>
  					<li class="list-group-item">
						<span><span style="float:left;"><b>Revenue Model:</b></span>
						<?php 
							if(!empty($revenueModelsArr)){
								foreach($revenueModelsArr as $revTg){
									echo '<span class="tagClass">'.$revTg.'</span>';
								}
							}
						?>
						</span>
  					</li>
  					
  				</ul>

		    </div>
		    <div class="col">
		    	<ul class="list-group">
  					<li class="list-group-item">
						<span><b>Date:</b> <?php echo $emailDateTime; ?></span>
						</li>
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
  						<span><b>Messages:</b> <a href="<?php echo site_url("threaddetails/$threadId"); ?>" target="_blank">Click here to view messages</a></span>
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

<!--- Pagination --->

<?php

//$pageurl = site_url('dashboard?p=');


$totalrecords = $TotalRecords;

$perpage = 10;
$totalpages = $totalrecords / $perpage;

if($totalpages > 1){ ?>
	<div id="pagination" class="pagination">
<?php if($page > 1){
?>

<a href="<?php echo $pageurl. ($page - 1).$queryString; ?>" class="pagesno">Prev</a>

<?php }

if(is_float ($totalpages)){
	$totalpages = ceil($totalpages);
}

$usepages = 5;

if($totalpages < 5){
	$usepages = $totalpages;
}

$urlparam = '';

for($i = 1; $i <= $usepages; $i++){
	if($i == $page){
		$selected = 'selected';
	}else{
		$selected = '';
	}

	if($page > $usepages){
		$remain = $page - $usepages;
		$temp = $remain + $i;
	
		if($page == $temp){
			$selected = 'selected';
		}else{
			$selected = '';
		}
?>
	<a href="<?php echo $pageurl. $temp; ?>" class="pagesno <?php echo $selected; ?>"><?php echo $temp; ?></a>

<?php
	} else {
?>
	<a href="<?php echo $pageurl. $i; ?>" class="pagesno <?php echo $selected; ?>"><?php echo $i; ?></a>
<?php }

}

if($page < $totalpages){
?>
	<a href="<?php echo $pageurl. ($page + 1).$queryString; ?>" class="pagesno">Next</a>

<?php } ?>
</div>
<?php } ?>


<!--- /pagination --->



<?php include("footer.php"); ?>    