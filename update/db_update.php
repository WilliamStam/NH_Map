<?php
/*
 * Date: 2012/07/24
 * Time: 9:47 AM
 */
$i = 1;

$sql[$i++]=array(
	"ALTER TABLE `nh_users` ADD `password` VARCHAR( 30 ) NULL DEFAULT NULL AFTER `name`;"
);

 if (isset($_GET['debug'])){
	 header("Content-Type: application/json");
	 echo json_encode($sql);
	 exit();
}

