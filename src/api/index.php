<?php

include("_inc.php");
include("api.php");

use Magrathea2\Debugger;
Debugger::Instance()
	->SetType(Debugger::LOG)
	->LogQueries(false);

$api = new MagratheaCloud\MagratheaCloudApi();

if(@$_GET["debug"] == "true") {
	$api->Debug();
	die;
}
$api->Run();

