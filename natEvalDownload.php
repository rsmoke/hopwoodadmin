<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContest.php');
//require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=FacApptDetail.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Evaluator LoginName', 'Evaluation', 'Comment', 'Entry ID', 'Evaluation DateStamp', 'Title', 'Document Name', 'Applicant Uniqname', 'Applicant FirstName','Applicant LastName', 'Applicant PenName', 'ClassLevel','Type', 'ContestInstance'));

// fetch the data
$queryNatEval = <<<SQL
-- SELECT `evaluator`,rating AS evaluation,comment,`entry_id`,created,
-- vc.title, vc.`document`,vc.`uniqname` AS applicantUniq,vc.`firstname`,vc.`lastname`,
-- vc.`penName`,(CASE WHEN vc.`classLevel` >= 20 THEN "G" ELSE "U" END) AS gradeLevel,
-- vc.`manuscriptType`,vc.`ContestInstance`
-- FROM vw_entrydetail_with_classlevel_currated AS vc

-- LEFT OUTER JOIN tbl_evaluations AS jev ON (vc.`EntryId` = jev.`entry_id`)

-- WHERE evaluator IN (SELECT DISTINCT uniqname FROM tbl_nationalcontestjudge) AND fwdToNational = 1

-- ORDER BY manuscriptType, evaluator, gradeLevel DESC, entry_id, created DESC
SELECT `evaluator`,rating AS evaluation,comment,`entry_id`,created,
vc.title, vc.`document`,vc.`uniqname` AS applicantUniq,vc.`firstname`,vc.`lastname`,
vc.`penName`,(CASE WHEN vc.`classLevel` >= 20 THEN "G" ELSE "U" END) AS gradeLevel,
vc.`manuscriptType`,vc.`ContestInstance`
FROM vw_entrydetail_with_classlevel_currated AS vc

LEFT OUTER JOIN tbl_evaluations AS jev ON (vc.`EntryId` = jev.`entry_id`)

WHERE evaluator IN (SELECT DISTINCT uniqname FROM tbl_nationalcontestjudge) AND fwdToNational = 1 AND jev.id =
(SELECT MAX(jev1.id)
FROM tbl_evaluations AS jev1
WHERE jev1.entry_id = jev.entry_id AND jev1.evaluator = jev.evaluator)

ORDER BY manuscriptType, evaluator, gradeLevel DESC, evaluation DESC
SQL;

if (!$rows = $db->query($queryNatEval)){
  die("Database query failed");
}

// loop over the rows, outputting them
while ($row = $rows->fetch_assoc()) fputcsv($output, $row);
