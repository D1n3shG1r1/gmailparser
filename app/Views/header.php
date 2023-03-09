<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    function validateForm(){
      var username = $("#typeEmailX-2").val();
      var password = $("#typePasswordX-2").val();


      if(username == ""){
        errorMsg("Please enter the username");
        return false;
      }else if(password == ""){
        errorMsg("Please enter the password");
        return false;
      }else{
        return true;
      }

    }

    function errorMsg(msg){
        alert(msg);
    }

</script>

<style>
  .errMsg{
    font-size: 11px;
    color: red;
    text-align: center;
    width: 100%;
  }
</style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="javascript:void(0);">Parsed Emails</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link disabled" aria-current="page" aria-disabled="true" href="javascript:void(0);">Dashboard</a>
            </li>
            <?php 

          if(current_url() != site_url("signin")){
          ?>
            
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" aria-disabled="true" href="<?php echo site_url("signout"); ?>">SignOut</a>
            </li>
          <?php }?>
          </ul>
          <?php 

          if(current_url() == site_url("dashboard")){
          ?>
            <form class="d-flex" action="<?php echo $pageurl.$page; ?>" method="GET">
            <label class="sr-only">From:</label>
            <input class="form-control me-2" type="date" placeholder="Start Date" aria-label="Start Date" name="from">
            <label class="sr-only">To:</label>
            <input class="form-control me-2" type="date" placeholder="From Date" aria-label="From Date" name="to">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
          <?php }
          ?>
        </div>
      </div>
    </nav>