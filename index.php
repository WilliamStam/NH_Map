<?php
/*
require_once('/inc/app.php');
$app = new app();
$app->set("t","hi");
$app->route("GET /",function() use ($app) {
		$t = $app->get("t");
		echo "woof".$t;
	});
$app->run();
exit();*/

date_default_timezone_set('Africa/Johannesburg');
setlocale(LC_MONETARY, 'en_ZA');
//ini_set('memory_limit', '256M');
if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}

$f3 = require('lib/f3/base.php');
require('lib/twig/Autoloader.php');
require('class.template.php');
$f3->set('AUTOLOAD', './|lib/');
$f3->set('PLUGINS', 'lib/f3/');
$f3->set('CACHE', false);

$f3->set('DEBUG', 2);

$cfg = array();
require_once('config.default.inc.php');
if (file_exists("config.inc.php")) {
	require_once('config.inc.php');
}

$f3->set('DB', new DB\SQL('mysql:host=' . $cfg['DB']['host'] . ';dbname=' . $cfg['DB']['database'] . '', $cfg['DB']['username'], $cfg['DB']['password']));
$f3->set('cfg', $cfg);


$f3->route('GET|POST /', function($f3){

		if (count($_POST)){
			$name = isset($_POST['name'])? $_POST['name']:"";
			$address = isset($_POST['address'])? $_POST['address']:"";

			if ($name && $address){
				$a = new \DB\SQL\Mapper($f3->get("DB"), "nh_users");
				$a->load(array('name=?',$name));
				$a->name = $name;
				$a->address = $address;

				$a->save();

			}

		}

		$data = $f3->get("DB")->exec("SELECT * FROM nh_users");



		$tmpl = new \template("map.tmpl","ui");
		$tmpl->data = json_encode($data);
		$tmpl->output();

		
	});



$f3->run();
function test_array($array) {
	header("Content-Type: application/json");
	echo json_encode($array);
	exit();
}