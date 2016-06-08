<?php
  header('Content-Type: application/pdf');
  //readfile("../contestfiles/" . $_GET['file']);
  readfile("../contestfiles/" . preg_replace('/[^-a-zA-Z0-9_\.]/', '', $_GET['file']));
?>
