<?php
global $_site_name;
$file_csv = $_site_name."-".date("Y-m-d").".csv";
header('Content-type: application/csv');
header(sprintf('Content-Disposition: attachment; filename="%s"',$file_csv));
readfile("./files/".$file_csv);

exit;
?>