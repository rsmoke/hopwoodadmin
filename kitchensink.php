<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configEnglishContestAdmin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/basicLib.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = false;
$_SESSION['isAdmin'] = false;
$sqlSelect = <<< _SQL
    SELECT *
    FROM tbl_contestadmin
    WHERE uniqname = '$login_name'
    ORDER BY uniqname
_SQL;
if (!$resAdmin = $db->query($sqlSelect)) {
    db_fatal_error("data insert issue", $db->error, $sqlSelect, $login_name);
exit;
}
if ($resAdmin->num_rows > 0) {
    $isAdmin = true;
    $_SESSION['isAdmin'] = true;
}

if ($isAdmin) {
    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Hopwood-Kitchensink.csv"');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array('uniqname', 'UMid', 'Name', 'Local_Address', 'Home_Address', 'ClassLevel', 'Grad_Yr-Mo/School/Dept/Major', 'Financial-Aid', 'Financial-Aid-Desciption', 'Title-of-Manuscript', 'Type-of-Manuscript', 'Name-of-Contest', 'Qualifying-Course', 'Qualifying-Instructor', 'Qualifying-Term_Year', 'Hometown-Newspaper', 'Publication-Name', 'Pen_Name'));

                $sqlSelect2 = <<<SQL
                    SELECT *
                    FROM  vw_kitchensink2
SQL;

    if (!$result = $db->query($sqlSelect2)) {
        db_fatal_error("data select issue", $db->error);
        exit;
    }

    // loop over the rows, outputting them
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    echo "You are not allowed to view this stuff!";
}
