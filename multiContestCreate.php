<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

if ($isAdmin){
  $fall_notes = 'Fall ' . date("Y");
  $winter_notes = 'Winter ' . (date("Y")+1);
  $date_open =  date("Y").'-09-01 12:00:00';
  $fall_date_closed = date("Y") .'-12-04 12:00:00';
  $winter_date_closed = (date("Y")+1) .'-01-29 12:00:00';

$sqlMultiInsert = <<< _SQL
  INSERT INTO `quilleng_ContestManager`.`tbl_contest`
  (
  `contestsID`,
  `date_open`,
  `date_closed`,
  `notes`,
  `edited_by`,
  `judgingOpen`,
  `status`)
  VALUES
  (1,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (5,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (6,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (7,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (8,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (9,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (10,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (15,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (16,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (17,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),
  (18,'$date_open','$fall_date_closed','$fall_notes',"srvr_scrpt",0,0),

  (2,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (11,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (12,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (19,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (20,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (21,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (22,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (23,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (24,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (25,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (26,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (31,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (32,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0),
  (33,'$date_open','$winter_date_closed','$winter_notes',"srvr_scrpt",0,0);
_SQL;

  if (!$resAdmin = $db->query($sqlMultiInsert)) {
    db_fatal_error("data insert issue", $db->error, $sqlSelect, $login_name);
    $_SESSION['flashMessage'] = "You already added the contests for this academic year!";
    redirect_to('contestAdmin.php');
  } else {
    $_SESSION['flashMessage'] = "You successfully added the contests for this academic year!";
    redirect_to('contestAdmin.php');
  }
}