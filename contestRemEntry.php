<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
// $contestid = $db->real_escape_string(htmlspecialchars($_POST['contestid']));

// $nowDate = date("Y-m-d H:i:s", (strtotime("now")));
// $sqlUpdate = <<<SQL
//     UPDATE tbl_contest
//     SET status = 1, edited_by = '$login_name', edited_on = '$nowDate'
//     WHERE id = $contestid
// SQL;
//     if(!$result = $db->query($sqlUpdate)){
//         db_fatal_error($db->error, "Applicant individual entry deletion - " . "Username=> " . $login_name . " - ", $sqlUpdate);
//         exit($user_err_message);
//     }

// Setup query
$sql = $db->prepare("UPDATE tbl_contest
    SET status = 1, edited_by = ?, edited_on = ?
    WHERE id = ?");

// Setup parameters
$sql->bind_param('ssi',$login_name,$nowDate,$contestid);

//Format, sanitize, variables to be used in teh query
$contestid = $db->real_escape_string(htmlspecialchars($_POST['contestid']));
$nowDate = date("Y-m-d H:i:s", (strtotime("now")));

// Perform
  if($sql->execute()){
    $_SESSION['flashMessage'] = "<span class='text-danger'>Successfully deleted contest instance</span>";
  } else {
    db_fatal_error("Execute failed:(" . $sql->errno . ") for user " . $login_name, $sql->error, $sql);
    exit($user_err_message);
  }

$db->close();
