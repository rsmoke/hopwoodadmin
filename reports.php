<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
$isAdmin = false;
$_SESSION['isAdmin'] = false;
$sqlSelect = <<< _SQL
SELECT *
FROM tbl_contestadmin
WHERE uniqname = '$login_name'
ORDER BY uniqname
_SQL;
if (!$resAdmin = $db->query($sqlSelect)) {
db_fatal_error("data insert issue", $db->error);
exit;
}
if ($resAdmin->num_rows > 0) {
$isAdmin = true;
$_SESSION['isAdmin'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>LSA-<?php echo "$contestTitle";?> Writing Contests</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LSA-English Writing Contests">
    <meta name="keywords" content="LSA-English, Hopwood, Writing, UniversityofMichigan">
    <meta name="author" content="LSA-MIS_rsmoke">
    <link rel="icon" href="img/favicon.ico">
    <script type='text/javascript' src='js/webforms2.js'></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"><!-- 3.3.1 -->
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/bootstrap-formhelpers.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/normalize.css" media="all">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/default.css" media="all">
    <style type="text/css">
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }
    input[type=number] {
    -moz-appearance:textfield;
    }
    </style>
    <base href=<?php echo URL ?>>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="ADMIN/index.php"><?php echo "$contestTitle";?></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Signed in as <?php echo $login_name;?><strong class="caret"></strong></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="index.php"><?php echo "$contestTitle";?> main</a>
                </li>
                <li>
                  <a href="https://weblogin.umich.edu/cgi-bin/logout">logout</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <?php if ($isAdmin) {
    ?>
    <div class="container"><!-- container of all things -->
    <div class="row clearfix">
      <div class="col-md-12">
        <div class="btn-toolbar pagination-centered" role="toolbar" aria-label="admin_button_toolbar">
          <div class="btn-group" role="group" aria-label="contest_management">
            <a id="backToIndexBtn" type="button" class="btn btn-xs btn-default" href="index.php">Back to Contest Management</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row clearfix">
      <div class="col-md-12">
        <div class="btn-toolbar pagination-centered" role="toolbar" aria-label="report_button_toolbar">
          <div class="btn-group" role="group" aria-label="contest_applicants">
            <button id="reportFinAidBtn" type="button" class="btn btn-success">Financial Aid</button>
          </div>
          <div class="btn-group" role="group" aria-label="contest_applicants">
            <button id="reportRecLetterBtn" type="button" class="btn btn-success">Reference Letter Required</button>
          </div>
          <div class="btn-group" role="group" aria-label="contest_applicants">
            <button id="reportEvaluationsBtn" type="button" class="btn btn-success">Evaluations</button>
          </div>
          <div class="btn-group" role="group" aria-label="contest_applicants">
            <button id="admApplicantBtn" type="button" class="btn btn-info"><i class="fa fa-download"></i> Total Applicant Information</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row clearfix">
      <div class="col-md-12">
        <div id="outputReports">
            <span id="outputReportData"></span>
        </div>
      </div>
    </div>
      <?php
      } else {
      ?>
      <!-- if there is not a record for $login_name display the basic information form. Upon submitting this data display the contest available section -->
      <div id="notAdmin">
        <div class="row clearfix">
          <div class="col-md-12">
            <div id="instructions" style="color:sienna;">
              <h1 class="text-center" >You are not authorized to this space!!!</h1>
              <h4>University of Michigan - LSA Computer System Usage Policy</h4>
              <p>This is the University of Michigan information technology environment. You
                MUST be authorized to use these resources. As an authorized user, by your use
                of these resources, you have implicitly agreed to abide by the highest
                standards of responsibility to your colleagues, -- the students, faculty,
                staff, and external users who share this environment. You are required to
                comply with ALL University policies, state, and federal laws concerning
                appropriate use of information technology. Non-compliance is considered a
                serious breach of community standards and may result in disciplinary and/or
              legal action.</p>
              <div style="postion:fixed;margin:10px 0px 0px 250px;height:280px;width:280px;"><a href="http://www.umich.edu"><img alt="University of Michigan" src="img/michigan.png" /> </a></div>
              </div><!-- #instructions -->
            </div>
          </div>
        </div>
        <?php
        }
        include("footer.php");?>
        <!-- //additional script specific to this page -->
        <script src="js/reportsScript.js"></script>
        </div><!-- End Container of all things -->
      </body>
    </html>
    <?php
    $db->close();