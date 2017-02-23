<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

$entryToUpdate = $_POST['entry_id'];

  $updateEntry = "UPDATE tbl_entry SET sendtoNationalJudging = 1 WHERE id = $entryToUpdate";

    $resUpdate = $db->query($updateEntry);

    if ($db->error) {
        try {
            throw new Exception("MySQL error $db->error <br> Query:<br> $updateEntry", $db->errno);
        } catch(Exception $e ) {
            echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
            echo nl2br($e->getTraceAsString());
        }
    } else {

    echo ("Entry " . $entryToUpdate . " was added to the National Judging stage!");
    }

    $db->close();
