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

 if ($isAdmin) {
    
    // $to = "um_ricks+p8lizbzwi3rwjww5f8vh@boards.trello.com"; // this is your Email address
    // $from = htmlspecialchars($_POST['email']); // this is the sender's Email address
    // $first_name = htmlspecialchars($_POST['first_name']);
    // $last_name = htmlspecialchars($_POST['last_name']);
    // $subject = "Hopwood WritingContest - " . htmlspecialchars($_POST['topic']) . " email=> " . $from . " " . date("Y/m/d h:i:sa");
    // $subject2 = "Copy of your Hopwood WritingContest comment or question form submission";
    // $messageFooter = "-- Please do not reply to this email. If you requested a reply or if we need more information, we will contact you at the email address you provided. --";
    // $message = "logged in as=> " . $login_name . " " . $first_name . " " . $last_name . " email=> " . $from . " wrote the following:" . "\n\n" . htmlspecialchars($_POST['message']);
    // $message2 = "Here is a copy of your message " . $first_name . ":\n\n" . htmlspecialchars($_POST['message']) . "\n\n" . $messageFooter;


    // $headers = "From:" . $from;
    // $headers2 = "From:" . $to;
    // mail($to,$subject,$message,$headers);
    // mail($from,$subject2,$message2, "From:english.department@umich.edu"); // sends a copy of the message to the sender
    // echo "<h4>Mail Sent.</h4> <p>Thank you " . $first_name . " for getting in touch! We’ve sent you a copy of this message at the email address you provided.<br> Have a great day!</p>";
    // echo "<a class='btn btn-info' href='index.php'>Return to Hopwood Writing Contest</a>";

    // $summarySection = "";
  $_SESSION['emailsentcount'] = 0;
  $emailSentCounter = 0;
    foreach($resultNatRatingEmail as $item){
      $mailSection = ""; //Clear mail body each time

      // $to = $item["uniqname"] . "@umich.edu";
      $to = "rsmoke" . "@umich.edu";
      $from = "hopwoodcontestnotify@umich.edu";
      $subject = "Hopwood Writing Contest - Judges Comments";

      $mailSection .= "Hello " . $item["author_fullname"] . ",";
      $mailSection .= "\n";
      $mailSection .= "Here are the comments you received for your <strong>" . $item["contest_name"] ."</strong> entry titled <em>" . $item["title"] . "</em>.";
      $mailSection .= "\n\n";
      $mailSection .= strlen($item["judge1"]) > 1 ? $item["judge1comments"] : "";
      $mailSection .= "\n";
      $mailSection .= $item["judge2"] <> $item["judge1"] ? $item["judge2comments"] : "";
      $mailSection .= "\n\n";
      $mailSection .= "-- Please do not reply to this email --\n";
      $mailSection .= "If you have any questions or comments about your entry, please contact the Hopwood Writing Contests at <a href='mailto:hopwoodcontestnotify@umich.edu'>Hopwood Contest Notify</a>";
      $mailSection .= "\n\nThank you";
      $headers = "From:" . $from;
      mail($to,$subject,$mailSection,$headers);
      $emailSentCounter++;
     };
     $_SESSION['emailsentcount'] = $emailSentCounter;
    
    echo '<div><h3 class="text-center">You have just sent ' . $_SESSION['emailsentcount'] . ' emails!</h3><br />';
    echo '<h4 class="text-center"><a href="index.php">Return to the home page</h4></div>';
    $db->close();
      } else {
        safeRedirect("index.php");
      }