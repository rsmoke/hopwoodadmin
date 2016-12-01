<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

  $queryFinAid = <<<SQL
  SELECT uniqname, userFname, userLname, tbl_entry.id, recletter1Name, recletter2Name
  FROM
    tbl_entry
        JOIN
    tbl_applicant ON (tbl_entry.applicantID = tbl_applicant.id)
  WHERE
    (recletter1Name <> "NoValue" OR recletter1Name <> "NoValue") AND status = 0
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
          'entryid' =>$item["id"],
          'recname1' => $item["recletter1Name"],
          'recname2' => $item["recletter2Name"]
          )

        );
      }
    }

  echo (json_encode(array("result" => $result)));

  $resSelect->free();
