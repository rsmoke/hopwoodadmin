<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

  $queryFinAid = <<<SQL
    -- SELECT vw.EntryId, rank.entryid, title, firstname, lastname, penName, contestName, rank, rankedby
    -- FROM `vw_entrydetail` AS vw
    -- JOIN tbl_ranking AS rank ON(vw.EntryID = rank.entryid)
    -- ORDER BY rank.rank DESC
    SELECT vw.EntryId, rank.entryid, title, firstname, lastname, penName, contestName, rank, rankedby
    FROM `vw_entrydetail` AS vw
    JOIN tbl_ranking AS rank ON(vw.EntryID = rank.entryid)
    WHERE rank.rank > 0
    ORDER BY rank.rank
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
          )

        );
      }
    }

  echo (json_encode(array("result" => $result)));

  $resSelect->free();