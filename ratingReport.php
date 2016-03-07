<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

  $queryFinAid = <<<SQL
    SELECT vw.EntryId, rank.entryid, title, firstname, lastname, penName, contestName, rank, rankedby, vw.document, rank.comment,vw.manuscriptType
    FROM `vw_entrydetail` AS vw
    JOIN tbl_ranking AS rank ON(vw.EntryID = rank.entryid)
    WHERE rank.rank > 0
    ORDER BY contestName, manuscriptType,rank.rankedby, rank.rank
SQL;

  $resSelect = $db->query($queryFinAid);
  if (!$resSelect) {
    echo "There is no information available";
  } else {
    $result = array();

      while($item = $resSelect->fetch_assoc()){
      array_push($result, array(
          'entryid' =>$item["entryid"],
          'title' =>$item["title"],
          'firstname' =>$item["firstname"],
          'lastname' =>$item["lastname"],
          'penName' =>$item["penName"],
          'contestName' => $item["contestName"],
          'rank' => $item["rank"],
          'rankedby' => $item["rankedby"],
          'document' => $item["document"],
          'comment' => $item["comment"],
          'manuscriptType' => $item["manuscriptType"]
          )

        );
      }
    }

  echo (json_encode(array("result" => $result)));

  $resSelect->free();
