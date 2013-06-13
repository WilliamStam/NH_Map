<?php
/*
 * Date: 2012/07/24
 * Time: 9:47 AM
 */
$i = 1;
$sql = array();
/*
$sql[$i++]=array(
	""
);
*/
 if (isset($_GET['debug'])){
	 header("Content-Type: application/json");
	 echo json_encode($sql);
	 exit();
}

