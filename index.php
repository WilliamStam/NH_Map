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
require('inc/class.template.php');
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


$f3->route('GET /logout', function($f3){
	unset($_SESSION['user']);
		$f3->reroute("/");
});
$f3->route('GET /remove', function($f3){
		$user = isset($_SESSION['user'])? $_SESSION['user']:"";

		if ($user['ID']){
			unset($_SESSION['user']);
			$a = new \DB\SQL\Mapper($f3->get("DB"), "nh_users");
			$a->load(array('ID=?',$user['ID']));
			$a->erase();
		}

		$f3->reroute("/");
});
$f3->route('GET|POST /', function($f3){

		$user = array();
		if (isset($_SESSION['user'])){
			$user = $_SESSION['user'];
		}




			$username = isset($_REQUEST['username'])? $_REQUEST['username']:"";
			$password = isset($_REQUEST['password'])? $_REQUEST['password']:"";
			$address = isset($_REQUEST['address'])? $_REQUEST['address']:"";

			if ($username && $password){

				$a = new \DB\SQL\Mapper($f3->get("DB"), "nh_users");
				$a->load(array('name=?',$username));
				$a->name = $username;


				if ($a->password==$password){
					$user = array(
						"ID"      => $a->ID,
						"name"    => $a->name,
						"address" => $a->address
					);
				} else {
					$user = array(
						"ID"      => "",
						"name"    => $username,
						"address" => ""
					);
					$a->password = $password;
					$a->save();
					if (!$a->dry()) {
						$user['ID'] = $a->_id;
					}

				}


				$_SESSION['user'] = $user;



			}

		if (isset($user['ID']) && $user['ID'] && $address){
			$a = new \DB\SQL\Mapper($f3->get("DB"), "nh_users");
			$a->load(array('ID=?',$user['ID']));
			$a->address = $address;
			$a->save();
			$user['address']=$address;
		}



		$data = $f3->get("DB")->exec("SELECT * FROM nh_users");



	//	test_array($user);
		$tmpl = new \template("map.tmpl","ui");
		$tmpl->data = json_encode($data);
		$tmpl->user = $user;
		$tmpl->output();

		
	});



$f3->run();
function test_array($array) {
	header("Content-Type: application/json");
	echo json_encode($array);
	exit();
}