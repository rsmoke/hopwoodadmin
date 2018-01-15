<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
//require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=National_Evaluations-printed_on-" . date('Y-m-d') . ".csv");

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('EntryId', 'Title', 'Contest_Name', 'Rating', 'Firstname', 'Lastname', 'umid', 'ClassLevel', 'Pen_Name', 'Evaluator', 'Document', 'Contestant_comment', 'Committee_comment', 'Manuscript_Type'));

// fetch the data
$queryLocalEval = <<<SQL
    SELECT
    vw.EntryId
    ,vw.title AS title
    ,vw.contestName As contestName
    ,CASE WHEN eval.rating >= 1 THEN eval.rating ELSE '' END AS rating
    ,vw.firstname AS firstname
    ,vw.lastname AS lastname
    ,vw.umid AS umid
    ,CASE WHEN vw.classLevel > 12 THEN 'G' ELSE 'U' END AS classLevel
    ,vw.penName AS penName
    ,eval.evaluator AS evaluator
    ,vw.document AS document
    ,eval.contestantcomment AS contestantcomment
    ,eval.committeecomment AS committeecomment
    ,vw.manuscriptType AS manuscriptType

    FROM `vw_entrydetail_with_classlevel_currated` AS vw
    JOIN vw_current_national_evaluations AS eval ON(vw.EntryID = eval.entry_id)
    WHERE created > '2017-12-06' AND fwdToNational = 1
    ORDER BY contestName, evaluator, -rating DESC
SQL;

if (!$rows = $db->query($queryLocalEval)){
  die("Database query failed");
}

// loop over the rows, outputting them
while ($row = $rows->fetch_assoc()) fputcsv($output, $row);
