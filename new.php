<?php 
$date= $_POST['picker'];
echo $unix = strtotime($date);
echo $time = $unix/86400+61730;
?>