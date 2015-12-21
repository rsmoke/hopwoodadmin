<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

  $queryFinAid = <<<SQL
  SELECT DISTINCT uniqname, finAidDesc, userFname, userLname
  FROM
    tbl_entry
        JOIN
    tbl_applicant ON (tbl_entry.applicantID = tbl_applicant.id)
  WHERE
    finAid = 1 AND status = 0
SQL;

  $resSelect = $db->query($queryFinAid);
  if (!$resSelect) {
    echo "There is no information available";
  } else {
    $result = array();
    while($item = $resSelect->fetch_assoc()){
    array_push($result, array(
        'uniqname' =>$item["uniqname"],
        'fname' =>$item["userFname"],
        'lname' =>$item["userLname"],
        'desc' =>$item["finAidDesc"]
        )

      );
    }
  }
  echo (json_encode(array("result" => $result)));

  $resSelect->free();