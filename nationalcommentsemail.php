<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

if (session_status() == PHP_SESSION_NONE) {
session_start();
}
//$_SESSION['flashMessage'] = "FLASHER";
$isAdmin = false;
$_SESSION['isAdmin'] = false;
$sqlSelect = <<< _SQL
SELECT *
FROM tbl_contestadmin
WHERE uniqname = '$login_name'
ORDER BY uniqname
_SQL;
if (!$resAdmin = $db->query($sqlSelect)) {
db_fatal_error("data read issue", $db->error, $sqlSelect, $login_name);
exit;
}
if ($resAdmin->num_rows > 0) {
$isAdmin = true;
$_SESSION['isAdmin'] = true;
}

// This is gets all the entries and national evals


$nat_rating_email = <<< _SQLNATRATINGEMAIL
SELECT 
cn.entry_id AS entryID
,ed.contestName AS contest_name
,ed.title AS title
,ed.uniqname AS uniqname
,CONCAT(ed.firstname, " ", ed.lastname) AS author_fullname
,MAX(CONCAT("<strong>Judge: ",CONCAT(nj.firstname, ' ',nj.lastname), " commented- </strong>",cn.contestantcomment)) AS judge1comments
,MIN(CONCAT("<strong>Judge: ",CONCAT(nj.firstname, ' ',nj.lastname), " commented- </strong>",cn.contestantcomment)) AS judge2comments
,MAX(cn.evaluator) AS judge1
,MIN(cn.evaluator) AS judge2
FROM quilleng_ContestManager.vw_current_national_evaluations AS cn
LEFT OUTER JOIN vw_entrydetail_with_classlevel_currated AS ed ON cn.entry_id = ed.EntryId
LEFT OUTER JOIN tbl_nationalcontestjudge AS nj ON cn.evaluator = nj.uniqname

GROUP BY entry_id
ORDER BY uniqname

_SQLNATRATINGEMAIL;

  $resNatRatingEmail = $db->query($nat_rating_email);
  $resultNatRatingEmail = array();
  
  if ($db->error) {
      try {    
          throw new Exception("MySQL error $db->error <br> Query:<br> $nat_rating_email", $db->errno);    
      } catch(Exception $e ) {
          echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
          echo nl2br($e->getTraceAsString());
      }
  }
  while($item = $resNatRatingEmail->fetch_assoc()){
    array_push($resultNatRatingEmail, 
      array(
        'entryID' =>$item["entryID"]
        ,'contest_name' =>$item["contest_name"]
        ,'title' =>$item["title"]
        ,'uniqname' =>$item["uniqname"]
        ,'author_fullname' =>$item["author_fullname"]
        ,'judge1' =>$item["judge1"]
        ,'judge2' =>$item["judge2"]
        ,'judge1comments' =>$item["judge1comments"]
        ,'judge2comments' =>$item["judge2comments"]

      )
    );
  }

 //print_r2 ($resultNatRatingEmail);

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
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="index.php"><?php echo "$contestTitle";?></a>
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
    <?php
    // for ($i=0;$i<sizeof($resultNatEntryEvalDetail);$i++){

    $summarySection = "";
    foreach($resultNatRatingEmail as $item){
      $summarySection .= "<div class='contest_email'>";
      $summarySection .= "<hr>";
      $summarySection .= "TO: " . $item["uniqname"] . "@umich.edu";
      $summarySection .= "<br />";
      $summarySection .= "FROM: hopwoodcontestnotify@umich.edu";
      $summarySection .= "<p>";
      $summarySection .= "Hello " . $item["author_fullname"] . ",";
      $summarySection .= "<br />";
      $summarySection .= "Here are the comments you received for your <strong>" . $item["contest_name"] ."</strong> entry titled <em>" . $item["title"] . "</em>.";
      $summarySection .= "</p><p>";
      $summarySection .= strlen($item["judge1"]) > 1 ? $item["judge1comments"] : "";
      $summarySection .= "</p><p>";
      $summarySection .= $item["judge2"] <> $item["judge1"] ? $item["judge2comments"] : "";
      $summarySection .= "</p><p>";
      $summarySection .= "<strong>-- Please do not reply to this email --</strong><br />";
      $summarySection .= "If you have any questions or comments about your entry, please contact the Hopwood Writing Contests at <a href='mailto:hopwoodcontestnotify@umich.edu'>Hopwood Contest Notify</a>";
      $summarySection .= "<p>Thank you</p>";

     };
     echo $summarySection;
     ?> 
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
        <script src="js/admMyScript.js"></script>
        </div><!-- End Container of all things -->
      </body>
    </html>
    <?php
    $db->close();
