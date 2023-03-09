<?php

 

$p = $userData["p"];
/*
$threadId = $userData["threadId"];
$email = $userData["email"];
$parm_fromName = $userData["parm_fromName"];
$parm_fromEmail = $userData["parm_fromEmail"];
$parm_toName = $userData["parm_toName"];
$parm_toEmail = $userData["parm_toEmail"];
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $page_title; ?></title>
    <style>
      body
{
position: relative;
font-family: 'Montserrat';
background-color: #f7f7f7;
height: 100vh;
}

figure,p,h1,h2,h3,h4,h5,h6
{
margin: 0;
}

.bottom-popup
{
/*max-height: 500px;*/
overflow-y: auto !important;
/*z-index: 99999999;*/
/*position: absolute;*/
bottom: 0;
right: 0;
/*width: 350px;*/
background: #fff;
box-shadow: 0 10px 25px -10px rgba(0,0,0,0.2);
/*border-top-left-radius: 20px;*/
overflow: hidden;
transition: all 0.2s linear;
}

.bottom-popup h3
{
background: #4D96FB;
color: #fff;
padding: 1.5rem;
text-align: center;
position: relative;
z-index: 1;
}


.bottom-popup h3 a
{
  position: absolute;
  top: 20px;
  left: 15px;
}
.bottom-popup h3 span
{
  text-align: right;
}

.bottom-popup form
{
padding: 2rem;
transition: all 0.2s linear;
}

.bottom-popup form label
{
margin-bottom: 5px;
font-weight: 500;
display: inline-block;
}

.bottom-popup input,
.bottom-popup textarea
{
background: #f7f7f7;
width: 100%;
padding: 15px;
color: #000;
height: 60px;
border: none !important;
outline: none;
display: block;
appearance: none;
}

.bottom-popup textarea
{
height: 180px;
}

.bottom-popup form .form-group
{
margin-bottom: 1.5rem;
}

.btn-save
{
display: inline-block;
font-weight: 400;
line-height: 1.5;
color: #fff;
text-align: center;
text-decoration: none;
vertical-align: middle;
cursor: pointer;
-webkit-user-select: none;
-moz-user-select: none;
user-select: none;
background-color: #4D96FB;
border: 1px solid transparent;
padding: 10px 25px;
font-size: 14px;
transition: all 0.2s linear;

}
.form-collapse
{
  overflow: hidden;
  position: relative;
}
.popup-open
{
  right: -19rem;
  transition: all 0.2s linear;
  display:block;
}
.popup-open form
{
  padding: 0;
  height: 0;
  position: relative;
  transition: all 0.2s linear;
  overflow: hidden;
}

.popup-open #closeParserBoxBttn{
  transform: rotate(135deg);
}

#button{width: 100%;}
#message{display:none;
    font-size: 10px;
    margin: 0;
    width: 100%;
}

.error{color: #fd021a;}
.success{color: #238758;}

    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url("assets/jquery-ui-1.13.1/jquery-ui.min.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/tagit/jquery.tagit.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("assets/jquery/jquery.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/jquery-ui-1.13.1/jquery-ui.min.js"); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <script src="<?php echo base_url("assets/tagit/tag-it.js");?>"></script>
    <script src="<?php echo base_url("assets/crypto-js/md5.js"); ?>"></script>
    <script src="<?php echo base_url("assets/crypto-js/aes.js"); ?>"></script>
<script>
 
var encKey = "#dk#";
var p = "<?php echo $p;?>";
var threadId = "";
var userEmail = "";
var parm_fromName = "";
var parm_fromEmail = "";
var parm_toName = "";
var parm_toEmail = "";

  $(function(){
    
    setTimeout(function(){

      var decryptedP = CryptoJS.AES.decrypt(p,encKey);
      var decryptedP = decryptedP.toString(CryptoJS.enc.Utf8);

      var decryptedPArr = decryptedP.split("|");
      userEmail = decryptedPArr[0];
      threadId = decryptedPArr[1];
      parm_fromName = decryptedPArr[2];
      parm_fromEmail = decryptedPArr[3];
      parm_toName = decryptedPArr[4];
      parm_toEmail = decryptedPArr[5];

      $("#SCIP_name").val(parm_fromName);
      $("#SCIP_from").val(parm_fromEmail);
      
      initTagit();

    }, 500);

  });

  function initTagit(){

      var sampleTags = [];
      
      $("#SCIP_industryTags").tagit({
          availableTags: sampleTags,
          allowSpaces: true,
          placeholderText: "Enter Industry tags"
      });


      $("#SCIP_technologyTags").tagit({
          availableTags: sampleTags,
          allowSpaces: true,
          placeholderText: "Enter Technology tags"
      });

      $("#SCIP_revenueModelTags").tagit({
          availableTags: sampleTags,
          allowSpaces: true,
          placeholderText: "Enter Revenue-Model tags"
      });
   
  }


  function isReal(arg){
    if(arg != "" && arg != null && arg != undefined){
      return true;
    }else{
      return false;
    }
  }

  function showError(msg,err){
    
    $("#message").html(msg);
    if(err == 0){
      $("#message").removeClass("error");
      $("#message").addClass("success");
    }else{
      $("#message").removeClass("success");
      $("#message").addClass("error");
    }
    
    $("#message").fadeIn("fast");

    setTimeout(function(){$("#message").fadeOut("slow");},3000);
  }

  function saveEmailBody(){
  
  
  var SCIP_name = $("#SCIP_name").val();
  var SCIP_from  = $("#SCIP_from").val();
  var SCIP_dealname = $("#SCIP_dealname").val();
  var SCIP_dealtype = $("#SCIP_dealtype").val();
  var SCIP_source = $("#SCIP_source").val();
  var SCIP_action = $("#SCIP_action").val();
  var SCIP_note = $("#SCIP_note").val();
  var SCIP_industryTags = $("#SCIP_industryTags").val();
  var SCIP_technologyTags = $("#SCIP_technologyTags").val();
  var SCIP_revenueModelTags = $("#SCIP_revenueModelTags").val();
  

 
  var msg = "";
  var err = 0;
  if(!isReal(userEmail)){
    msg = "Invalid User! Please contact to administrator.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(threadId)){
    msg = "Invalid Thread! Please contact to administrator.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_name)){
    msg = "Please enter Name.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_from)){
    msg = "Please enter From.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_dealname)){
    msg = "Please enter Deal Name.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_dealtype)){
    msg = "Please select Deal Type.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_source)){
    msg = "Please select Deal Source.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_action)){
    msg = "Please select Deal Action.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_note)){
    msg = "Please enter the Note.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_industryTags)){
    msg = "Please enter the tags for Industry.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_technologyTags)){
    msg = "Please enter the tags for Technology.";
    err = 1;
    showError(msg,err);
    return false;
  }else if(!isReal(SCIP_revenueModelTags)){
    msg = "Please enter the tags for Revenue Model.";
    err = 1;
    showError(msg,err);
    return false;
  }else{

    var btn = $("#button");
    btn.button('loading');
    
    setTimeout(function(){
      
      var requestUrl = "<?php echo site_url("save"); ?>";
      
      $.ajax({
        url:requestUrl,
        data:{
            "userEmail": userEmail,
            "threadId": threadId,
            "SCIP_name":SCIP_name,
            "SCIP_from":SCIP_from,
            "SCIP_dealname":SCIP_dealname,
            "SCIP_dealtype":SCIP_dealtype,
            "SCIP_source":SCIP_source,
            "SCIP_action":SCIP_action,
            "SCIP_note":SCIP_note,
            "SCIP_industryTags":SCIP_industryTags,
            "SCIP_technologyTags":SCIP_technologyTags,
            "SCIP_revenueModelTags":SCIP_revenueModelTags
          },
        dataType:"json",
        type:"POST",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(resp){

            if(resp.C == 100){
              msg = "Data saved successfully.";
              err = 0;
            }else if(resp.C == 102){
              msg = "Thread is already saved in db.";
              err = 1;
            }else{
              msg = "It seems something went wrong. Please try again.";
              err = 1;
            }
            showError(msg,err);
            btn.button('reset');
            closeWindow(); 
          
        },
        error: function(p1, p2, p3){

          console.log("p1");
          console.log(p1);
          console.log("p2");
          console.log(p2);
          console.log("p3");
          console.log(p3);

          msg = "It seems something went wrong. Please try again.";
          err = 1;
          showError(msg,err);
          btn.button('reset');
          closeWindow(); 
        }


      });
    }, 3000);
  }

}

function closeWindow(){
  setTimeout(function(){
    Window.close();
    //window.opener.close();
  },3000);
}
</script>
</head>
<body>
  <div id="SCIPParserBox" class="bottom-popup">
    <h3>Save to SCIP</h3>

<form action="javascript:void(0);" method="post">
  <div class="form-group">
    <label for="SCIP_name">Name</label>
    <input type="text" class="form-control" id="SCIP_name" placeholder="Enter Assignee Name">
  </div>

  <div class="form-group">
    <label for="SCIP_from">From</label>
    <input type="text" class="form-control" id="SCIP_from" placeholder="Enter From Name">
  </div>


  <div class="form-group">
    <label for="SCIP_dealname">Name of Deal</label>
    <input type="text" class="form-control" id="SCIP_dealname" placeholder="Enter the Deal Name">
  </div>


  <div class="form-group">
    <label for="SCIP_dealtype">Deal Type</label>
    <select class="form-control" id="SCIP_dealtype">
      <option value="">Select the Deal Type</option>
      <option value="new deal">New Deal</option>
      <option value="existing deal">Existing Deal</option>
    </select>
  </div>


  <div class="form-group">
    <label for="SCIP_source">Source</label>
    <select class="form-control" id="SCIP_source">
      <option value="">Select the source</option>
      <option value="investor">Investor</option>
      <option value="incubator">Incubator</option>
      <option value="accelerator">Accelerator</option>
      <option value="hackathon">Hackathon</option>
      <option value="co-investment">Co-investment</option>
    </select>
  </div>

  <div class="form-group">
    <label for="SCIP_action">Action</label>
    <select class="form-control" id="SCIP_action">
      <option value="">Select the action</option>
      <option value="send to apply">Send to apply</option>
      <option value="evaluate">Evaluate</option>
      <option value="scoring">Scoring</option>
      <option value="workflow">Workflow</option>
      <option value="reject">Reject</option>
    </select>
  </div>

  <div class="form-group">
    <label for="SCIP_note">Note</label>
    <textarea class="form-control" id="SCIP_note" placeholder="Enter Text Here" rows="5" maxlength="200"></textarea>
  </div>

  <div class="form-group">
    <label for="SCIP_note">Industry</label>
    <input type="text" id="SCIP_industryTags" placeholder="Type to search Industry">
  </div>

  <div class="form-group">
    <label for="SCIP_note">Technology</label>
    <input type="text" id="SCIP_technologyTags" placeholder="Type to search Technology">
  </div>

  <div class="form-group">
    <label for="SCIP_note">Revenue Model</label>
    <input type="text" id="SCIP_revenueModelTags" placeholder="Type to search Revenue Model">
  </div>
  
  <div class="form-group" style="text-align: center;">
    <button id="button" type="submit" onclick="saveEmailBody();" class="btn btn-primary mb-2" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing Data">Save</button>
    <label id="message"></label>
  </div>
  
</form>
</div>
</body>
</html>